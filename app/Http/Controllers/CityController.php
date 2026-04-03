<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;
use App\Models\Offer;



class CityController extends Controller
{
    // Free weight allowance in kilograms before extra weight fees apply
    const FREE_WEIGHT_LIMIT_KG = 25;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }

    public function getCitiesByState($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        if (app()->getLocale() == 'fr') {
            foreach ($cities as $city) {
                $city->name_en = $city->name_ar;
            }
        }
        return response()->json($cities);
    }

    public function getAreasByCity($city_id)
    {
        $areas = \App\Models\Area::where('city_id', $city_id)->get();
        if (app()->getLocale() == 'fr') {
            foreach ($areas as $area) {
                $area->name_en = $area->name_ar;
            }
        }
        return response()->json($areas);
    }

    public function getAreaCharge($area_id)
    {
        $delivery_area = DeliveryCharge::where('area_id', $area_id)->with(['area', 'city.state.country'])->first();
        // Fallback to city charge if no area charge?
        // But we seeded all areas with 0 charge.
        // If not found, maybe fallback to City (Wilayat) then State (Governorate).

        // But wait, the seeder created DeliveryCharge for each Area.
        // So we should find it if it exists.

        $charge = $delivery_area ? $delivery_area->charge : 0;

        // Logic similar to getCityCharge for tax/offers/etc.
        // Copying logic from getCityCharge but adapting for Area.

        $subtotal = floatval(subtotal());
        $countryNameForTax = null;
        if ($delivery_area && $delivery_area->state && $delivery_area->state->country) {
            $countryNameForTax = $delivery_area->state->country->id; // Use ID for helper
        } elseif ($delivery_area && $delivery_area->city && $delivery_area->city->state && $delivery_area->city->state->country) {
            $countryNameForTax = $delivery_area->city->state->country->id; // Use ID for helper
        }

        $tax = tax_amount($subtotal, $countryNameForTax);
        $coupon = Session::get('CouponAmount', 0);
        $weight_charge = $this->calculateExtraWeightFees();

        $free_shipping = Setting::where('slug', 'free_shipping')->value('value') ?? 0;
        $free_shipping_offer = Offer::where('type', 'free_shipping_with_total_bill')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('status', 1)
            ->where('minimum_total', '<=', $subtotal)
            ->first();

        if ($free_shipping_offer) {
            $weight_charge = 0;
            $charge = 0;
        }
        if ($free_shipping == 1) {
            $weight_charge = 0;
            $charge = 0;
        }

        // Check for User Subscription (Active and valid date)
        $activeSubscription = null;
        if (auth()->check()) {
            $activeSubscription = \App\Models\UserSubscription::where('user_id', auth()->id())
                ->where('status', 'active')
                ->whereDate('end_at', '>=', now())
                ->with('subscription')
                ->latest()
                ->first();

            if ($activeSubscription && $activeSubscription->subscription) {
                if ($activeSubscription->subscription->free_shipping) {
                    $weight_charge = 0;
                    $charge = 0;
                }
                if ($activeSubscription->subscription->tax_exempt) {
                    $tax = 0;
                }
            }
        }

        $subtotal_After_offer = $subtotal;
        $offers = Offer::where('type', 'total_bill_discount')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('status', 1)
            ->orderBy('minimum_total', 'desc')
            ->get();
        $is_offer = false;
        $offer_Discount_value = 0;
        foreach ($offers as $offer) {
            if ($subtotal >= $offer->minimum_total) {
                $subtotal_After_offer = $subtotal - ($subtotal * $offer->discount_value / 100);
                $is_offer = true;
                $offer_Discount_value = $offer->discount_value;
                break;
            }
        }
        $subscription_discount = 0;
        if (!empty($activeSubscription) && $activeSubscription->subscription && $activeSubscription->subscription->discount_percent > 0) {
            $subscription_discount = ($subtotal_After_offer * $activeSubscription->subscription->discount_percent) / 100;
            $subtotal_After_offer -= $subscription_discount;
        }

        $gross_total = $subtotal_After_offer + $charge + $weight_charge + $tax - $coupon;
        $net_payable = $gross_total;

        // Wallet Deduction
        $wallet_used = 0;
        if (auth()->check() && !auth()->user()->is_admin) {
            $balance = auth()->user()->balance;
            if ($balance > 0) {
                if ($balance >= $net_payable) {
                    $wallet_used = $net_payable;
                    $net_payable = 0;
                } else {
                    $wallet_used = 0;
                    // $net_payable remains full (no partial payment)
                }
            }
        }

        return response()->json([
            'delivery_charge' => $charge,
            'formatted_charge' => currencyConverter($charge),
            'tax' => $tax,
            'tax_show' => currencyConverter($tax),
            'weight_charge' => $weight_charge,
            'weight_charge_show' => currencyConverter($weight_charge),
            'total_cost' => currencyConverter($gross_total), // Gross Total
            'net_payable' => currencyConverter($net_payable), // Net Payable
            'subtotal_After_offer' => currencyConverter($subtotal_After_offer),
            'subtotal' => currencyConverter($subtotal),
            'is_offer' => $is_offer,
            'offer_Discount' => currencyConverter($offer_Discount_value),
            'subscription_discount' => currencyConverter($subscription_discount),
            'wallet_used' => currencyConverter($wallet_used),
        ]);

    }


    public function getCityCharge($city_id)
    {
        $delivery_city = DeliveryCharge::where('city_id', $city_id)->with(['city', 'state.country'])->first();

        $charge = $delivery_city ? $delivery_city->charge : 0;
        $subtotal = floatval(subtotal());
        $countryNameForTax = null;
        if ($delivery_city && $delivery_city->state && $delivery_city->state->country) {
            $countryNameForTax = $delivery_city->state->country->id; // Use ID for helper
        }

        $tax = tax_amount($subtotal, $countryNameForTax);
        $coupon = Session::get('CouponAmount', 0);
        $weight_charge = $this->calculateExtraWeightFees();

        $free_shipping = Setting::where('slug', 'free_shipping')->value('value') ?? 0;

        $free_shipping_offer = Offer::where('type', 'free_shipping_with_total_bill')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('status', 1)
            ->where('minimum_total', '<=', $subtotal)
            ->first();

        if ($free_shipping_offer) {
            $weight_charge = 0;
            $charge = 0;
        }


        if ($free_shipping == 1) {
            $weight_charge = 0;
            $charge = 0;
        }

        // Check for User Subscription (Active and valid date)
        if (auth()->check()) {
            $activeSubscription = \App\Models\UserSubscription::where('user_id', auth()->id())
                ->where('status', 1)
                ->whereDate('end_at', '>=', now())
                ->with('subscription')
                ->latest()
                ->first();

            if ($activeSubscription && $activeSubscription->subscription) {
                if ($activeSubscription->subscription->free_shipping) {
                    $weight_charge = 0;
                    $charge = 0;
                }
                if ($activeSubscription->subscription->tax_exempt) {
                    $tax = 0;
                }
            }
        }

        $subtotal_After_offer = $subtotal;
        $offers = Offer::where('type', 'total_bill_discount')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('status', 1)
            ->orderBy('minimum_total', 'desc')
            ->get();
        $is_offer = false;
        $offer_Discount_value = 0;
        foreach ($offers as $offer) {
            if ($subtotal >= $offer->minimum_total) {
                $subtotal_After_offer = $subtotal - ($subtotal * $offer->discount_value / 100);
                $is_offer = true;
                $offer_Discount_value = $offer->discount_value;
                break;
            }
        }
        $total_cost = $subtotal_After_offer + $charge + $weight_charge + $tax - $coupon;

        // Wallet Deduction
        $wallet_used = 0;
        if (auth()->check() && !auth()->user()->is_admin) {
            $balance = auth()->user()->balance;
            if ($balance > 0) {
                if ($balance >= $total_cost) {
                    $wallet_used = $total_cost;
                    $total_cost = 0;
                } else {
                    $wallet_used = 0;
                    // $total_cost remains full
                }
            }
        }

        return response()->json([
            'delivery_charge' => $charge,
            'formatted_charge' => currencyConverter($charge),
            'tax' => $tax,
            'tax_show' => currencyConverter($tax),
            'weight_charge' => $weight_charge,
            'weight_charge_show' => currencyConverter($weight_charge),
            'total_cost' => currencyConverter($total_cost),
            'subtotal_After_offer' => currencyConverter($subtotal_After_offer),
            'subtotal' => currencyConverter($subtotal),
            'is_offer' => $is_offer,
            'offer_Discount' => $offer_Discount_value . '%',
            'wallet_used' => currencyConverter($wallet_used),
        ]);

    }

    public function calculateExtraWeightFees()
    {
        $totalWeightGrams = 0;
        foreach (Cart::content() as $item) {
            $itemWeight = $item->options->weight->weight ?? 0;
            $totalWeightGrams += $itemWeight * $item->qty;
        }
        $totalWeightKg = $totalWeightGrams / 1000;

        $shippingFee = 0;
        if ($totalWeightKg > self::FREE_WEIGHT_LIMIT_KG) {
            $extraKg = ceil($totalWeightKg - self::FREE_WEIGHT_LIMIT_KG);
            $shippingFee = ($extraKg * 0.100);
        }

        return $shippingFee;
    }
}
