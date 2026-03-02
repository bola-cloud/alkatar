<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $data['title'] = __('Subscriptions');
        $data['subscriptions'] = Subscription::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.pages.subscriptions.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Create Subscription');
        return view('admin.pages.subscriptions.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'period_type' => 'required',
            'period_value' => 'required|integer|min:1',
        ]);

        $slug = Str::slug($request->name) . '-' . uniqid();
        Subscription::create(array_merge($request->only(['name', 'period_type', 'period_value', 'price', 'discount_percent', 'max_discount_amount', 'free_shipping', 'tax_exempt', 'description', 'is_active']), ['slug' => $slug]));
        return redirect()->route('admin.subscriptions')->with('success', __('Successfully Created'));
    }

    public function edit(Subscription $subscription)
    {
        $data['title'] = __('Edit Subscription');
        $data['subscription'] = $subscription;
        return view('admin.pages.subscriptions.edit', $data);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required|string',
            'period_type' => 'required',
            'period_value' => 'required|integer|min:1',
        ]);
        $subscription->update($request->only(['name', 'period_type', 'period_value', 'price', 'discount_percent', 'max_discount_amount', 'free_shipping', 'tax_exempt', 'description', 'is_active']));
        return redirect()->route('admin.subscriptions')->with('success', __('Successfully Updated'));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions')->with('success', __('Successfully Deleted'));
    }

    public function users(Subscription $subscription)
    {
        $data['title'] = $subscription->name . ' - ' . __('Subscribers');
        $data['subscription'] = $subscription;
        $data['users'] = \App\Models\UserSubscription::where('subscription_id', $subscription->id)->with('user')->orderBy('id', 'desc')->get();
        return view('admin.pages.subscriptions.users', $data);
    }
}
