<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Admin\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'subscribe' => 'required|email|unique:subscribes,Subscribe',
        ], [
            'subscribe.required' => __('The email field is required.'),
            'subscribe.email' => __('Please enter a valid email address.'),
            'subscribe.unique' => __('This email is already subscribed.'),
        ]);

        Subscribe::create([
            'Subscribe' => $request->subscribe
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Subscription successful!')
            ]);
        }

        return redirect()->back()->with('success', __('Subscription successful!'));
    }
}
