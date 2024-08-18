<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function couponApply(Request $request)
    {
        $user = Auth::user();
         $validated = $request->validate([
            'coupon_code' => 'required|string',
            'subtotal' => 'required|string',
        ]);
        $couponDetails = Coupon::where('CouponCode', $validated['coupon_code'])->first();
        if (!$couponDetails) {
            return response()->json(['error' => __('Coupon does not exist!')], 404);
        }
        if ($couponDetails->Status == 0) {
            return response()->json(['error' => __('Coupon Code is not active!')], 400);
        }
        $expire_date = $couponDetails->ExpireDate;
        $current_date = Carbon::now()->toDateString();
        if ($expire_date < $current_date) {
            return response()->json(['error' => __('Coupon Code is expired!')], 400);
        }
        if ($validated['subtotal'] < $couponDetails->Min_Expenses) {
            return response()->json(['error' => __('You have to spend a minimum of ' . $couponDetails->Min_Expenses . ' USD')], 422);
        }
        $couponAmount = $couponDetails->Amount;
        return response()->json([
            'success' => __('Coupon Code successfully applied!'),
            'coupon_id' => $couponDetails->id,
            'coupon_amount' => $couponAmount,
            'coupon_code' => $request->coupon_code,
        ], 200);
    }
}
