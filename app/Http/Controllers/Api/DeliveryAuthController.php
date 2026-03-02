<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\DeliveryMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliveryAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = DeliveryMan::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => __('Invalid credentials')], 401);
        }

        if (!$user->status) {
            return response()->json(['success' => false, 'message' => __('Account is inactive')], 403);
        }

        $token = $user->createToken('delivery_app_token')->plainTextToken;

        $user->total_orders = $user->orders()->where('Order_Status', ORDER_DELIVERED)->count();
        $user->total_earnings = $user->orders()->where('Order_Status', ORDER_DELIVERED)->sum('Delivery_Charge');

        return response()->json([
            'success' => true,
            'message' => __('Login successful'),
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->total_orders = $user->orders()->where('Order_Status', ORDER_DELIVERED)->count();
        $user->total_earnings = $user->orders()->where('Order_Status', ORDER_DELIVERED)->sum('Delivery_Charge');

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => __('Logged out successfully')]);
    }
}