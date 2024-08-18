<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryChargeResource;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function calculateDeliveryCharge(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|integer|exists:cities,id',
        ]);
        if ($validated['city_id'] != null) {
            $deliveryCharge = DeliveryCharge::where('city_id', $validated['city_id'])->where('status', ACTIVE)->first();
        }
        return  DeliveryChargeResource::make($deliveryCharge);

    }
}
