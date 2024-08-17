<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $deliveryCharge = delivery_charge($validated['city_id']);
        return response()->json([
            'success' => true,
            'delivery_charge' => $deliveryCharge,
        ], 200);
    }
}
