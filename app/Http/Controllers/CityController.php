<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;



class CityController extends Controller
{
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
        return response()->json($cities);
    }

    public function calculateExtraWeightFees()
    {
        $totalWeightGrams = 0;
        foreach (Cart::content() as $item) {
            $itemWeight = $item->options->weight->weight ?? 0;

            $totalWeightGrams += $itemWeight * $item->qty;
        }

        // Convert grams to kilograms
        $totalWeightKg = $totalWeightGrams / 1000;

        $shippingFee = 0;

        if ($totalWeightKg >= 1 && $totalWeightKg <= 10) {
            $shippingFee = 2; // 2 OMR for 1-10kg
        } elseif ($totalWeightKg > 10) {
            $extraKg = ceil($totalWeightKg - 10);
            $shippingFee = 2 + ($extraKg * 0.100); // 2 OMR + 0.100 OMR for each extra kg
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

        $total_cost = $subtotal + $charge + $weight_charge + $tax - $coupon;

        return response()->json([
            'delivery_charge' => $charge,
            'formatted_charge' => currencyConverter($charge),
            'total_cost' => currencyConverter($total_cost),
        ]);
    }
}
