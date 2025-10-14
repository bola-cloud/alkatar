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

    public function calculateExtraWeightFees()
    {
        $free_shipping = Setting::where('slug', 'free_shipping')->get()[0]['value'];
        $subtotal = Cart::subtotal();
        $free_shipping_offer = Offer::where('type', 'free_shipping_with_total_bill')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('status', 1)
            ->where('minimum_total', '<=', $subtotal)
            ->first();

        if ($free_shipping_offer) {
            return 0;
        }

        if ($free_shipping == 1) {
            return 0;
        }

        $totalWeightGrams = 0;
        foreach (Cart::content() as $item) {
            $itemWeight = $item->options->weight->weight ?? 0;
            $totalWeightGrams += $itemWeight * $item->qty;
        }
        $totalWeightKg = $totalWeightGrams / 1000;

        $shippingFee = 0;

        if ($totalWeightKg > self::FREE_WEIGHT_LIMIT_KG) {
            $extraKg = ceil($totalWeightKg - self::FREE_WEIGHT_LIMIT_KG);
            $shippingFee = ($extraKg * 0.100); //  0.100 OMR for each extra kg
        }
        return $shippingFee;
    }


    public function getCityCharge($city_id)
    {
        $delivery_city = DeliveryCharge::where('city_id', $city_id)->first();

        $charge = $delivery_city ? $delivery_city->charge : 0;
        $subtotal = Cart::subtotal();
        $tax = tax_amount($subtotal, 1);
        $coupon = Session::get('CouponAmount', 0);
        $weight_charge = $this->calculateExtraWeightFees();

        $free_shipping = Setting::where('slug', 'free_shipping')->get()[0]['value'];

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

        return response()->json([
            'delivery_charge' => $charge,
            'formatted_charge' => currencyConverter($charge),
            'total_cost' => currencyConverter($total_cost),
            'subtotal_After_offer' => currencyConverter($subtotal_After_offer),
            'subtotal' => currencyConverter($subtotal),
            'is_offer' => $is_offer,
            'offer_Discount' => $offer_Discount_value . '%',
        ]);
    }
}
