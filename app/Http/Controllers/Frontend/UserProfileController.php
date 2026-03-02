<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\Admin\Order;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function userProfile()
    {
        $authId = Auth::user()->id;
        $data['user'] = User::where('id', $authId)->first();
        // Load user's recent orders so the profile page can display orders and tracking
        $orders = Order::with(['order_details', 'order_details.product'])->where('User_Id', $authId)->latest()->get();

        // Fetch Subscriptions
        $data['subscriptions'] = \App\Models\Subscription::where('is_active', true)->get();
        $data['current_subscription'] = \App\Models\UserSubscription::where('user_id', $authId)
            ->where('status', 'active')
            ->where('end_at', '>', now())
            ->with('subscription')
            ->latest()
            ->first();

        // expose the full set of orders to the profile view so the UI can render actual items
        // attach a few computed attributes so the frontend JS can render invoice/call and timestamps
        foreach ($orders as $order) {
            // printable invoice url
            $order->print_url = route('order.print', ['id' => $order->id]);
            // try to read shipping address (stored as JSON) for contact details
            $shipping = null;
            if (!empty($order->shipping_address)) {
                $shipping = $order->shipping_address;
            }
            $order->delivery_name = $shipping['name'] ?? ($order->user->name ?? null);
            $order->delivery_phone = $shipping['phone_number'] ?? ($order->user->Number ?? null);
            // friendly timestamps for tracking steps
            $order->confirmed_at = $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('j M Y h:i A') : null;
            $order->delivered_at = !empty($order->Delivery_At) ? \Carbon\Carbon::parse($order->Delivery_At)->format('j M Y h:i A') : null;
        }
        $data['all_orders'] = $orders; // previously filtered; show all orders here
        $data['delivered_orders'] = $orders->where('Order_Status', ORDER_DELIVERED);
        $data['canceled_orders'] = $orders->whereIn('Order_Status', [ORDER_CANCELLED, ORDER_DELIVERED_FAILED, ORDER_RETURN]);
        $data['title'] = __('User Panel');
        $data['description'] = __('User Panel');
        $data['keywords'] = __('User Panel');
        return view('front.pages.user_profile.profile', $data);
    }
    public function userProfileEdit()
    {
        $authId = Auth::user()->id;
        $data['user'] = User::where('id', $authId)->first();
        $data['title'] = __('User Panel');
        $data['description'] = __('User Panel');
        $data['keywords'] = __('User Panel');
        // Reuse the single canonical profile view so the form is rendered only once
        // (prevents duplicate tab sets / duplicate forms appearing on the page)
        return view('front.pages.user_profile.profile', $data);
    }
    public function userProfileUpdate(Request $request)
    {
        // Accept either combined name or first_name + last_name
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'number' => 'required',
            'password' => 'nullable|confirmed|min:6',
            'dob' => 'nullable|date',
            'offer_types' => 'nullable|array'
        ], [
            'email.required' => __('Email is required'),
            'number.required' => __('Phone number is required'),
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        // Build the name value
        if ($request->has('first_name') || $request->has('last_name')) {
            $first = $request->input('first_name', '');
            $last = $request->input('last_name', '');
            $name = trim($first . ' ' . $last);
        } else {
            $name = $request->input('name', Auth::user()->name);
        }

        if (!empty($request->image)) {
            $image = fileUpload($request['image'], AdminProfilePicture());
        } else {
            $authId = Auth::user()->id;
            $update = User::where('id', $authId)->first();
            $image = $update->image;
        }

        $authId = Auth::user()->id;

        $updateData = [
            'name' => $name,
            'email' => $request->email,
            'image' => $image,
            'street_address' => $request->street_address,
            'Number' => $request->number,
            'Gender' => $request->gender ?? null,
            'DOB' => $request->dob ?? null,
            'About' => $request->about ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }
        // Save offer subscription types (array of strings)
        if ($request->has('offer_types')) {
            $updateData['offer_types'] = $request->input('offer_types');
        } else {
            $updateData['offer_types'] = [];
        }

        $user = User::where('id', $authId)->update($updateData);
        if ($user) {
            return redirect()->back()->with('success', __('Successfully Updated!'));
        }
        return redirect()->back()->with('success', __('Something Went Wrong!'));
    }
    public function myOrder()
    {
        $authId = Auth::user()->id;

        $orders = Order::with(['order_details', 'order_details.product'])->where('User_Id', $authId)->latest()->get();
        foreach ($orders as $order) {
            $order->print_url = route('order.print', ['id' => $order->id]);
            $shipping = null;
            if (!empty($order->shipping_address)) {
                $shipping = $order->shipping_address;
            }
            $order->delivery_name = $shipping['name'] ?? ($order->user->name ?? null);
            $order->delivery_phone = $shipping['phone_number'] ?? ($order->user->Number ?? null);
            $order->confirmed_at = $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('j M Y h:i A') : null;
            $order->delivered_at = !empty($order->Delivery_At) ? \Carbon\Carbon::parse($order->Delivery_At)->format('j M Y h:i A') : null;
        }
        $data['all_orders'] = $orders; // return full list for my orders page as well
        $data['delivered_orders'] = $orders->where('Order_Status', ORDER_DELIVERED);
        $data['canceled_orders'] = $orders->whereIn('Order_Status', [ORDER_CANCELLED, ORDER_DELIVERED_FAILED, ORDER_RETURN]);
        $data['title'] = __('Orders');
        $data['description'] = __('Orders');
        $data['keywords'] = __('Orders');
        return view('front.pages.user_profile.my_order', $data);
    }
    public function myReview()
    {
        $data['reviews'] = ProductReview::where('user_id', Auth::id())->with('product')->get();
        $data['title'] = __('Reviews');
        $data['description'] = __('Reviews');
        $data['keywords'] = __('Reviews');
        return view('front.pages.user_profile.my_review', $data);
    }
    public function trackMyOrder($id)
    {
        $id = decrypt($id);
        $data['order'] = Order::where('id', $id)->with('order_details', 'order_details.product')->first();
        $data['title'] = __('Order Track');
        $data['description'] = __('Order Track');
        $data['keywords'] = __('Order Track');
        return view('front.pages.user_profile.track_my_order', $data);
    }

    public function reviewStore(ReviewStoreRequest $request)
    {
        $prev_review = ProductReview::where('product_id', $request->product_id)->where('user_id', Auth::id())->first();
        if (!empty($prev_review)) {
            $update = $prev_review->update([
                'feedback' => $request->feedback,
                'rating' => $request->rating,
            ]);
            if (!empty($update)) {
                return redirect()->back()->with('success', 'Your review is successfully updated!');
            }
        }
        $store = ProductReview::create([
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
        ]);
        if (!empty($store)) {
            return redirect()->back()->with('success', 'Review Successfully');
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }
}
