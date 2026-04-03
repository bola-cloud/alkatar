@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    <style>
        /* Profile page custom styles to match provided design */
        .profile-wrapper {
            max-width: 980px;
            margin: 0 auto;
        }

        .nav-tabs {
            justify-content: center;
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            color: #6b6b6b;
            font-weight: 700;
            padding: 12px 26px;
            border: none;
            border-radius: 0;
        }

        .nav-tabs .nav-link.active {
            color: #6aa21a;
            position: relative;
        }

        .nav-tabs .nav-link.active::after {
            content: "";
            height: 6px;
            width: 86px;
            background: #2a6b2a;
            display: block;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -18px;
            border-radius: 4px;
        }

        .form-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control.custom {
            border: 1px solid #d8d8d8;
            border-radius: 8px;
            padding: 12px 14px;
            height: 44px;
            box-shadow: none;
            background: #fff;
        }

        .form-control.custom:focus {
            box-shadow: none;
            border-color: #9fbf2a;
        }

        .card.p-4 {
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .profile-avatar {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .avatar-fallback {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #b5c61a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
        }

        .subscriptions {
            margin-top: 6px;
        }

        .subscriptions .form-check {
            margin-right: 18px;
        }

        .btn-save {
            background: #b5c61a;
            color: #ffffff;
            border: 2px solid rgba(0, 0, 0, 0.12);
            border-radius: 40px;
            padding: 14px 36px;
            font-weight: 700;
            letter-spacing: 1px;
            width: 75%;
            margin: 38px auto 0;
            display: block;
        }

        .btn-save:active,
        .btn-save:focus {
            box-shadow: none;
        }

        /* birthday input group icon */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon .icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #2a6b2a;
        }

        @media (max-width: 768px) {
            .btn-save {
                width: 100%;
            }

            .nav-tabs .nav-link.active::after {
                bottom: -12px;
            }
        }

        /* dashed (lined) outer border */
        .profile-border {
            border: 4.24px dashed #007349;
            /* lined border as requested */
            border-radius: 16px;
            padding: 0;
            /* inner panel handles spacing */
            display: block;
            background: transparent;
            box-sizing: border-box;
        }

        /* inner white panel that holds the content */
        .profile-border .profile-inner {
            background: #ffffff;
            border-radius: 12px;
            padding: 42px 48px;
            /* inner spacing to match previous layout */
        }
    </style>

    <div class="container py-5 profile-wrapper">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-account" data-bs-toggle="tab" data-bs-target="#account"
                            type="button" role="tab">{{ __('My Account') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-orders" data-bs-toggle="tab" data-bs-target="#orders" type="button"
                            role="tab">{{ __('profile.my_orders') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#address"
                            type="button" role="tab">{{ __('profile.my_address') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-subscription" data-bs-toggle="tab" data-bs-target="#subscription"
                            type="button" role="tab">{{ __('Subscription') }}</button>
                    </li>
                </ul>

                <div class="profile-border mt-5">
                    <div class="profile-inner">
                        <div class="tab-content">
                            {{-- My Account Tab --}}
                            <div class="tab-pane fade show active" id="account" role="tabpanel"
                                aria-labelledby="tab-account">
                                <div class="card p-4">
                                    <form enctype="multipart/form-data" action="{{ route('user.profile.update') }}"
                                        method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('First Name') }}</label>
                                                <input type="text" class="form-control custom" name="first_name"
                                                    value="{{ old('first_name', explode(' ', $user->name)[0] ?? $user->name) }}">
                                                @error('name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                                @error('first_name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Last Name') }}</label>
                                                <input type="text" class="form-control custom" name="last_name"
                                                    value="{{ old('last_name', count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '') }}">
                                                @error('last_name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Contact Number') }}</label>
                                                <input type="text" class="form-control custom" name="number"
                                                    value="{{ old('number', $user->Number) }}">
                                                @error('number')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Email') }}</label>
                                                <input type="email" class="form-control custom" name="email"
                                                    value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Date Of Birth') }}</label>
                                                <div class="input-with-icon">
                                                    <input type="text" class="form-control custom" name="dob"
                                                        placeholder="mm/dd/yyyy"
                                                        value="{{ old('dob', $user->DOB ? \Carbon\Carbon::parse($user->DOB)->format('m/d/Y') : '') }}">
                                                    <span class="icon"><i class="bi bi-chevron-down"></i></span>
                                                </div>
                                                @error('dob')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Password') }}</label>
                                                <input type="password" class="form-control custom" name="password"
                                                    placeholder="">
                                                @error('password')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">{{ __('Confirm Password') }}</label>
                                                <input type="password" class="form-control custom"
                                                    name="password_confirmation" placeholder="">
                                                @error('password_confirmation')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 mb-3 mt-4">
                                                <label
                                                    class="d-block mb-2"><strong>{{ __('profile.offer_subscription') }}:</strong></label>
                                                <div class="subscriptions mt-2">
                                                    @php
                                                        $userOffers = is_array($user->offer_types) ? $user->offer_types : [];
                                                    @endphp
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="offer_whatsapp"
                                                            name="offer_types[]" value="whatsapp" {{ in_array('whatsapp', $userOffers) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="offer_whatsapp">{{ __('profile.whatsapp') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="offer_email"
                                                            name="offer_types[]" value="email" {{ in_array('email', $userOffers) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="offer_email">{{ __('Email') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="offer_sms"
                                                            name="offer_types[]" value="sms" {{ in_array('sms', $userOffers) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="offer_sms">{{ __('profile.sms_text') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid mt-4">
                                                    <button type="submit"
                                                        class="btn-save">{{ __('profile.save_changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                {{-- Footer content shown on all tabs --}}
                                <div class="row d-flex justify-content-center mt-4">
                                    <div class="col-md-5 d-flex justify-content-center">
                                        <img src="{{ asset('new-design/images/security.png') }}" style="height: 35vh;">
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center mt-3">
                                    <div class="col-md-6 d-flex justify-content-center">
                                        <div style="color: #2D5D2A; text-align: center;">
                                            Access your profile to manage your orders, save your favorites, and never miss a
                                            hot deal again
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- My Orders Tab --}}
                            <div class="tab-pane fade p-5" id="orders" role="tabpanel" aria-labelledby="tab-orders">
                                <style>
                                    /* Orders grid styles */
                                    .orders-toolbar {
                                        display: flex;
                                        justify-content: flex-end;
                                    }

                                    .btn-filter {
                                        background: #fff;
                                        border-radius: 28px;
                                        padding: 8px 14px;
                                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                                        border: 1px solid rgba(0, 0, 0, 0.06);
                                        color: #222;
                                        font-weight: 700;
                                        display: inline-flex;
                                        gap: 8px;
                                        align-items: center;
                                    }

                                    .btn-filter i {
                                        font-size: 16px;
                                    }

                                    .order-grid {
                                        display: flex;
                                        flex-wrap: wrap;
                                        gap: 24px;
                                    }

                                    .order-card {
                                        border: 1.5px solid #2a6b2a;
                                        border-radius: 12px;
                                        padding: 20px;
                                        width: 100%;
                                        background: #fff;
                                        box-shadow: 0 2px 0 rgba(0, 0, 0, 0.02);
                                    }

                                    @media(min-width: 768px) {
                                        .order-card {
                                            width: calc(50% - 12px);
                                        }
                                    }

                                    .order-head {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        margin-bottom: 8px;
                                    }

                                    .order-head .id {
                                        font-weight: 700;
                                        color: #0b3b0b;
                                    }

                                    .order-status {
                                        color: #ff7a00;
                                        font-weight: 600;
                                    }

                                    .order-meta {
                                        color: #7b7b7b;
                                        font-size: 14px;
                                        margin: 10px 0;
                                    }

                                    .order-summary {
                                        font-weight: 700;
                                        color: #c62828;
                                        margin-bottom: 12px;
                                    }

                                    .order-actions {
                                        display: flex;
                                        gap: 12px;
                                    }

                                    .btn-view,
                                    .btn-track {
                                        border-radius: 40px;
                                        padding: 8px 18px;
                                        font-weight: 700;
                                        text-transform: uppercase;
                                        font-size: 13px;
                                    }

                                    .btn-view {
                                        background: transparent;
                                        color: #2a6b2a;
                                        border: 2px solid #dfeede;
                                    }

                                    .btn-track {
                                        background: #b5c61a;
                                        color: #fff;
                                        border: 0;
                                    }

                                    /* RTL fixes: keep visual layout consistent with LTR while
                                                                    respecting text direction. These rules ensure headers,
                                                                    meta and summary align correctly in Arabic. */
                                    html[dir="rtl"] .order-head {
                                        direction: rtl;
                                        text-align: right;
                                    }

                                    html[dir="rtl"] .order-meta,
                                    html[dir="rtl"] .order-summary {
                                        text-align: right;
                                    }

                                    html[dir="rtl"] .order-actions {
                                        justify-content: flex-start;
                                    }

                                    /* panel header classes will be flipped via flex-direction */
                                    .order-panel-header {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        margin-bottom: 12px;
                                    }

                                    html[dir="rtl"] .order-panel-header {
                                        flex-direction: row-reverse;
                                    }
                                </style>

                                <div class="orders-toolbar mb-5">
                                    {{-- <button class="btn-filter" type="button"><span>{{ __('Filter') }}</span> <i
                                            class="bi bi-funnel"></i></button> --}}
                                </div>

                                <div class="order-grid">
                                    @if(!empty($all_orders) && count($all_orders))
                                        @foreach($all_orders as $order)
                                            @php
                                                // Map order status to a readable stage for the frontend
                                                $stage = 'confirmed';
                                                if ($order->Order_Status == ORDER_PROCESSING)
                                                    $stage = 'processing';
                                                if ($order->Order_Status == ORDER_SHIPPED)
                                                    $stage = 'shipped';
                                                if ($order->Order_Status == ORDER_DELIVERED)
                                                    $stage = 'delivered';
                                                if (in_array($order->Order_Status, [ORDER_CANCELLED, ORDER_RETURN, ORDER_DELIVERED_FAILED]))
                                                    $stage = 'cancelled';

                                                // compute item count and total (prefer order totals if present)
                                                $itemCount = optional($order->order_details)->sum('qty');
                                                if (!$itemCount) {
                                                    $itemCount = optional($order->order_details)->count() ?: 0;
                                                }

                                                // Use Order model fields (database columns) when available
                                                $subtotal = $order->Sub_Total ?? 0;
                                                $tax = $order->Tax ?? 0;
                                                $delivery = $order->Delivery_Charge ?? 0;
                                                $total = $order->Grand_Total ?? $order->grand_total ?? $order->total ?? 0;
                                                if (!$total && !empty($order->order_details)) {
                                                    $sum = 0;
                                                    foreach ($order->order_details as $d) {
                                                        $price = $d->price ?? $d->unit_price ?? 0;
                                                        $qty = $d->qty ?? $d->quantity ?? 1;
                                                        $sum += (float) $price * (int) $qty;
                                                    }
                                                    $total = $sum;
                                                }

                                                // get status key mapping and translate via lang files
                                                $statusLabel = $order->Order_Status;
                                                $statusKeyMap = [
                                                    ORDER_PENDING => 'pending',
                                                    ORDER_PROCESSING => 'processing',
                                                    ORDER_SHIPPED => 'shipped',
                                                    ORDER_DELIVERED => 'delivered',
                                                    ORDER_CANCELLED => 'cancelled',
                                                    ORDER_RETURN => 'returned',
                                                    ORDER_NOT_PAYMENT_YET => 'not_paid',
                                                    ORDER_DELIVERED_FAILED => 'delivery_failed',
                                                ];
                                                $statusKey = isset($statusKeyMap[$order->Order_Status]) ? $statusKeyMap[$order->Order_Status] : null;
                                                if ($statusKey) {
                                                    $statusLabel = __('orders.status.' . $statusKey);
                                                }

                                                $odata = [
                                                    'id' => $order->id,
                                                    'status' => $order->Order_Status ?? 'Out For Delivery',
                                                    'status_label' => $statusLabel,
                                                    'stage' => $stage,
                                                    'created_at' => optional($order)->created_at ? \Carbon\Carbon::parse($order->created_at)->format('j M Y h:i A') : '',
                                                    'items' => [],
                                                    'item_count' => (int) $itemCount,
                                                    'subtotal' => (float) $subtotal,
                                                    'tax' => (float) $tax,
                                                    'delivery' => (float) $delivery,
                                                    'grand_total' => (float) $total
                                                ];
                                                if (!empty($order->order_details)) {
                                                    foreach ($order->order_details as $d) {
                                                        // OrderDetails uses 'Product_Name', 'Price', 'Quantity', and may have 'Image'
                                                        // Choose localized product name when available. For Arabic UI we prefer
                                                        // 'fr_Product_Name' (site stores Arabic text there) even if a generic
                                                        // 'Product_Name' exists on the order detail.
                                                        $locale = session('HTML_LANG') ?? app()->getLocale();
                                                        $name = null;

                                                        // Helper: try several candidate fields first on order detail, then on product
                                                        $tryCols = [];
                                                        if (in_array($locale, ['ar', 'fr'])) {
                                                            // prefer fr_* for Arabic because ar_* isn't present in this dataset
                                                            $tryCols = ['fr_Product_Name', 'fr_product_name', 'ar_Product_Name', 'ar_product_name', 'Product_Name', 'product_name', 'name', 'title', 'en_Product_Name', 'en_product_name'];
                                                        } else {
                                                            $tryCols = ['en_Product_Name', 'en_product_name', 'Product_Name', 'product_name', 'fr_Product_Name', 'fr_product_name', 'ar_Product_Name', 'ar_product_name', 'name', 'title'];
                                                        }

                                                        // check order-detail fields first
                                                        foreach ($tryCols as $col) {
                                                            if (isset($d->{$col}) && !empty($d->{$col})) {
                                                                $name = $d->{$col};
                                                                break;
                                                            }
                                                        }

                                                        // IMPORTANT: If locale is Arabic and the RELATED product record
                                                        // contains an Arabic value in the fr_* field, prefer that value
                                                        // even if the order_detail snapshot (e.g. Product_Name) exists
                                                        // in English. This forces display of the correct Arabic name.
                                                        if (in_array($locale, ['ar', 'fr']) && !empty($d->product)) {
                                                            if (!empty($d->product->fr_Product_Name)) {
                                                                $name = $d->product->fr_Product_Name;
                                                            } elseif (!empty($d->product->fr_product_name)) {
                                                                $name = $d->product->fr_product_name;
                                                            }
                                                        }

                                                        // if still empty, check related product properties (fallback)
                                                        if (empty($name) && !empty($d->product)) {
                                                            $prod = $d->product;
                                                            foreach ($tryCols as $col) {
                                                                if (isset($prod->{$col}) && !empty($prod->{$col})) {
                                                                    $name = $prod->{$col};
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        if (empty($name)) {
                                                            // last-resort: fall back to existing generic field on detail
                                                            $name = $d->Product_Name ?? 'Item';
                                                        }
                                                        $qty = $d->Quantity ?? ($d->qty ?? 1);
                                                        $price = $d->Price ?? ($d->price ?? 0);
                                                        // Prefer the order-detail image snapshot, otherwise product primary image
                                                        $img = null;
                                                        if (!empty($d->Image)) {
                                                            // OrderDetails->Image is stored as filename; use ProductImage() path
                                                            $img = asset(ProductImage() . $d->Image);
                                                        } elseif (!empty($d->product) && !empty($d->product->Primary_Image)) {
                                                            $img = asset(ProductImage() . $d->product->Primary_Image);
                                                        }

                                                        $odata['items'][] = [
                                                            'name' => $name,
                                                            'qty' => (int) $qty,
                                                            'price' => (float) $price,
                                                            'image' => $img,
                                                        ];
                                                    }
                                                }
                                            @endphp
                                            <div class="order-card" data-id="{{ $odata['id'] }}" data-order-number="{{ $order->Order_Number }}" data-stage="{{ $odata['stage'] }}"
                                                data-created="{{ $odata['created_at'] }}"
                                                data-item-count="{{ $odata['item_count'] ?? 0 }}"
                                                data-subtotal="{{ number_format($odata['subtotal'] ?? 0, 3, '.', '') }}"
                                                data-tax="{{ number_format($odata['tax'] ?? 0, 3, '.', '') }}"
                                                data-delivery="{{ number_format($odata['delivery'] ?? 0, 3, '.', '') }}"
                                                data-grand="{{ number_format($odata['grand_total'] ?? ($odata['total'] ?? 0), 3, '.', '') }}"
                                                data-subtotal-formatted="{{ currencyConverter($odata['subtotal'] ?? 0) }}"
                                                data-tax-formatted="{{ currencyConverter($odata['tax'] ?? 0) }}"
                                                data-delivery-formatted="{{ currencyConverter($odata['delivery'] ?? 0) }}"
                                                data-grand-formatted="{{ currencyConverter($odata['grand_total'] ?? ($odata['total'] ?? 0)) }}"
                                                data-items='@json($odata['items'])'
                                                data-print-url="{{ $order->print_url ?? route('order.print', $order->id) }}"
                                                data-delivery-name="{{ $order->delivery_name ?? ($order->user->name ?? '') }}"
                                                data-delivery-phone="{{ $order->delivery_phone ?? ($order->user->Number ?? '') }}"
                                                data-confirmed-at="{{ $order->confirmed_at ?? (optional($order)->created_at ? \Carbon\Carbon::parse($order->created_at)->format('j M Y h:i A') : '') }}"
                                                data-processing-at="{{ $order->processing_at ?? '' }}"
                                                data-shipped-at="{{ $order->shipped_at ?? '' }}"
                                                data-delivered-at="{{ $order->delivered_at ?? (!empty($order->Delivery_At) ? \Carbon\Carbon::parse($order->Delivery_At)->format('j M Y h:i A') : '') }}">
                                                <div class="order-head">
                                                    <div class="id">{{ __('Order No:') }} <span
                                                            class="text-muted">#{{ $order->Order_Number }}</span></div>
                                                    <div class="order-status">
                                                        {{ $odata['status_label'] ?? ($order->status_label ?? ($order->Order_Status ?? __('Pending'))) }}
                                                    </div>
                                                </div>
                                                <div class="order-meta">
                                                    {{ optional($order)->created_at ? \Carbon\Carbon::parse($order->created_at)->format('j M Y h:i A') : '5 Feb 2025 12:20 Am' }}
                                                </div>
                                                <div class="order-summary">{{ __('Items') }} -
                                                    {{ currencyConverter($odata['grand_total'] ?? ($odata['total'] ?? 0)) }}
                                                </div>
                                                <div class="order-actions">
                                                    <button type="button" class="btn-view"
                                                        data-action="view">{{ __('View Details') }}</button>
                                                    <a href="{{ route('order.print', $order->id) }}" target="_blank"
                                                        class="btn-view">{{ __('Invoice') }}</a>
                                                    <button type="button" class="btn-track"
                                                        data-action="track">{{ __('Track Order') }}</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- My Address Tab --}}
                            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="tab-address">
                                <style>
                                    .address-grid {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 18px;
                                    }

                                    .address-card {
                                        border-radius: 12px;
                                        padding: 18px;
                                        border: 1px solid #e6e6e6;
                                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
                                        display: flex;
                                        align-items: center;
                                        gap: 18px;
                                    }

                                    .address-icon {
                                        width: 44px;
                                        height: 44px;
                                        border-radius: 50%;
                                        background: #fff6ea;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: #ff7a00;
                                        font-weight: 700;
                                    }

                                    .address-body {
                                        flex: 1
                                    }

                                    .address-title {
                                        font-weight: 700;
                                        color: #2a6b2a
                                    }

                                    .address-line {
                                        color: #6b6b6b;
                                        font-size: 14px
                                    }

                                    .btn-add-orange {
                                        background: #ff8a00;
                                        color: #fff;
                                        border-radius: 8px;
                                        padding: 8px 14px;
                                        font-weight: 700;
                                        display: inline-flex;
                                        gap: 8px;
                                        align-items: center;
                                        border: none;
                                    }

                                    .address-header {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        margin-top: 12px;
                                    }

                                    .address-pin {
                                        width: 36px;
                                        height: 36px;
                                        border-radius: 8px;
                                        background: #fff6ea;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: #ff7a00;
                                        font-weight: 700;
                                    }

                                    .address-map {
                                        height: 180px;
                                        background: #f6f2e8;
                                        border-radius: 8px;
                                        display: block;
                                        width: 100%
                                    }

                                    .address-form-panel {
                                        background: #fff;
                                        border-radius: 8px;
                                        padding: 22px;
                                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
                                        border: 1px solid rgba(0, 0, 0, 0.02);
                                    }

                                    .address-input {
                                        border: 1px solid #e6e6e6;
                                        border-radius: 10px;
                                        padding: 12px 14px;
                                        height: 46px;
                                    }

                                    .address-textarea {
                                        border: 1px solid #e6e6e6;
                                        border-radius: 10px;
                                        padding: 12px 14px;
                                        min-height: 80px;
                                    }

                                    .btn-save-address {
                                        background: #b5c61a;
                                        color: #fff;
                                        border-radius: 40px;
                                        padding: 14px 40px;
                                        font-weight: 700;
                                        border: 0;
                                        display: block;
                                        width: 100%;
                                        max-width: 420px;
                                        margin: 18px auto 0;
                                        text-align: center;
                                    }

                                    .address-form-row {
                                        display: flex;
                                        gap: 16px;
                                        flex-wrap: wrap;
                                    }

                                    .address-form-row .col {
                                        flex: 1;
                                        min-width: 200px;
                                    }

                                    .address-top-row .col-half {
                                        flex: 1;
                                        min-width: 240px;
                                    }

                                    .address-small-grid {
                                        display: grid;
                                        grid-template-columns: 1fr 1fr;
                                        gap: 12px;
                                    }

                                    @media (max-width:768px) {
                                        .address-header {
                                            flex-direction: column;
                                            align-items: flex-start;
                                            gap: 12px
                                        }
                                    }
                                </style>

                                <div class="address-grid">
                                    {{-- Map / hero placeholder --}}
                                    <div class="address-map"></div>

                                    <div class="address-header">
                                        <div style="display:flex;align-items:center;gap:12px">
                                            <div class="address-pin">📍</div>
                                            <div style="font-weight:700;">Address Information</div>
                                        </div>
                                        <div>
                                            <button class="btn-add-orange" type="button" id="add-new-address"><i
                                                    class="bi bi-plus-lg"></i>&nbsp;Add New Address</button>
                                        </div>
                                    </div>

                                    <div class="address-list">
                                        @php
                                            $addresses = isset($user) && method_exists($user, 'addresses') ? $user->addresses()->orderByDesc('is_default')->get() : collect();
                                        @endphp
                                        @if($addresses->isNotEmpty())
                                            @foreach($addresses as $addr)
                                                <div class="address-card" data-address='@json($addr)'>
                                                    <div class="address-icon">📍</div>
                                                    <div class="address-body">
                                                        <div class="address-title">
                                                            {{ $addr->label ?? ($addr->recipient_name ?? 'Home') }}
                                                            @if($addr->is_default) <span
                                                            class="badge bg-success ms-2">{{ __('Default') }}</span>@endif <span
                                                                class="text-muted">{{ $addr->city ?? '' }}</span>
                                                        </div>
                                                        <div class="address-line">
                                                            {{ $addr->address_line1 }}@if($addr->address_line2),
                                                            {{ $addr->address_line2 }}@endif
                                                        </div>
                                                        <div class="address-line small text-muted">{{ $addr->phone ?? '' }}
                                                            @if($addr->postal_code) · {{ $addr->postal_code }}@endif
                                                        </div>
                                                    </div>
                                                    <div style="display:flex;flex-direction:column;gap:8px">
                                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                                            data-action="edit">{{ __('Edit') }}</button>
                                                        <button class="btn btn-outline-danger btn-sm" type="button"
                                                            data-action="delete">🗑</button>
                                                        @if(!$addr->is_default)
                                                            <button class="btn btn-outline-primary btn-sm" type="button"
                                                                data-action="set-default">{{ __('Set Default') }}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- placeholder card --}}
                                            <div class="address-card"
                                                data-address='{"id":null,"label":"Home","address_line1":"Marassi, Sidi Abd El Rahman, Kilo 129, Egypt"}'>
                                                <div class="address-icon">📍</div>
                                                <div class="address-body">
                                                    <div class="address-title">Home</div>
                                                    <div class="address-line">Marassi, Sidi Abd El Rahman, Kilo 129, Egypt</div>
                                                </div>
                                                <div>
                                                    <button class="btn btn-outline-danger btn-sm" type="button"
                                                        data-action="delete">🗑</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- My Subscription Tab --}}
                            <div class="tab-pane fade p-5" id="subscription" role="tabpanel"
                                aria-labelledby="tab-subscription">
                                <style>
                                    .subscription-card {
                                        border: 1.5px solid #e0e0e0;
                                        border-radius: 12px;
                                        padding: 24px;
                                        transition: all 0.3s ease;
                                        position: relative;
                                        height: 100%;
                                    }

                                    .subscription-card.active-plan {
                                        border-color: #b5c61a;
                                        background-color: #f9fbe7;
                                    }

                                    .subscription-card:hover {
                                        transform: translateY(-5px);
                                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
                                    }

                                    .plan-name {
                                        font-size: 20px;
                                        font-weight: 800;
                                        color: #2a6b2a;
                                        margin-bottom: 8px;
                                    }

                                    .plan-price {
                                        font-size: 32px;
                                        font-weight: 700;
                                        color: #333;
                                        margin-bottom: 16px;
                                    }

                                    .plan-period {
                                        font-size: 14px;
                                        color: #777;
                                        font-weight: 400;
                                    }

                                    .plan-features {
                                        list-style: none;
                                        padding: 0;
                                        margin: 20px 0;
                                    }

                                    .plan-features li {
                                        margin-bottom: 12px;
                                        color: #555;
                                        display: flex;
                                        align-items: center;
                                        gap: 10px;
                                    }

                                    .plan-features li i {
                                        color: #b5c61a;
                                    }

                                    .btn-subscribe {
                                        width: 100%;
                                        border-radius: 8px;
                                        padding: 12px;
                                        font-weight: 700;
                                        background: #2a6b2a;
                                        color: #fff;
                                        border: none;
                                        transition: background 0.2s;
                                    }

                                    .btn-subscribe:hover {
                                        background: #1f501f;
                                    }

                                    .current-badge {
                                        position: absolute;
                                        top: 12px;
                                        right: 12px;
                                        background: #b5c61a;
                                        color: #fff;
                                        padding: 4px 12px;
                                        border-radius: 20px;
                                        font-size: 12px;
                                        font-weight: 700;
                                    }
                                </style>

                                {{-- Current Subscription Status --}}
                                @if(isset($current_subscription))
                                    <div class="mb-5">
                                        <h4 class="mb-4" style="font-weight: 700; color: #2a6b2a;">
                                            {{ __('Current Subscription') }}
                                        </h4>
                                        <div class="card p-4"
                                            style="border: 1px solid #b5c61a; background: #fcfdf5; border-radius: 12px;">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h5 class="mb-2" style="font-weight: 800;">
                                                        {{ $current_subscription->subscription->name ?? 'Plan' }}
                                                    </h5>
                                                    <p class="mb-1 text-muted">{{ __('Status') }}: <span
                                                            class="badge bg-success">{{ __('Active') }}</span></p>
                                                    <p class="mb-0 text-muted">{{ __('Expires on') }}:
                                                        <strong>{{ \Carbon\Carbon::parse($current_subscription->end_at)->format('d M Y') }}</strong>
                                                    </p>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <button class="btn btn-outline-success" disabled>{{ __('Active') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Available Plans --}}
                                <h4 class="mb-4" style="font-weight: 700; color: #2a6b2a;">{{ __('Available Plans') }}</h4>
                                <div class="row">
                                    @if(isset($subscriptions) && count($subscriptions) > 0)
                                        @foreach($subscriptions as $plan)
                                            <div class="col-md-4 mb-4">
                                                <div
                                                    class="subscription-card {{ (isset($current_subscription) && $current_subscription->subscription_id == $plan->id) ? 'active-plan' : '' }}">
                                                    @if(isset($current_subscription) && $current_subscription->subscription_id == $plan->id)
                                                        <span class="current-badge">{{ __('Current') }}</span>
                                                    @endif
                                                    <div class="plan-name">{{ __($plan->name) }}</div>
                                                    <div class="plan-price">
                                                        {{ currencySymbol()[currency()] }} {{ number_format($plan->price, 3) }}
                                                        <span class="plan-period">/ {{ $plan->period_value }}
                                                            {{ __($plan->period_type) }}</span>
                                                    </div>
                                                    <ul class="plan-features">
                                                        <li><i class="fas fa-check-circle"></i>
                                                            {{ __($plan->description ?? 'Premium Access') }}</li>
                                                        @if($plan->free_shipping)
                                                            <li><i class="fas fa-shipping-fast"></i> {{ __('Free Shipping') }}</li>
                                                        @endif
                                                        @if($plan->discount_percent > 0)
                                                            <li><i class="fas fa-tags"></i> {{ $plan->discount_percent }}%
                                                                {{ __('Discount') }}
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    @if(!isset($current_subscription) || $current_subscription->subscription_id != $plan->id)
                                                        <form action="{{ route('user.subscription.pay') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="subscription_id" value="{{ $plan->id }}">
                                                            <button type="submit"
                                                                class="btn-subscribe">{{ __('Subscribe Now') }}</button>
                                                        </form>
                                                    @else
                                                        <button class="btn-subscribe" style="background: #ccc; cursor: not-allowed;"
                                                            disabled>{{ __('Subscribed') }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <p class="text-muted">{{ __('No subscription plans available at the moment.') }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Subscription History --}}
                                <div class="mt-5">
                                    <h4 class="mb-4" style="font-weight: 700; color: #2a6b2a;">
                                        {{ __('Subscription History') }}
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Plan') }}</th>
                                                    <th>{{ __('Start Date') }}</th>
                                                    <th>{{ __('End Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $history = \App\Models\UserSubscription::where('user_id', auth()->id())->latest()->get();
                                                @endphp
                                                @if($history->count() > 0)
                                                    @foreach($history as $sub)
                                                        <tr>
                                                            <td>{{ $sub->subscription->name ?? '-' }}</td>
                                                            <td>{{ $sub->start_at ? \Carbon\Carbon::parse($sub->start_at)->format('d M Y') : '-' }}
                                                            </td>
                                                            <td>{{ $sub->end_at ? \Carbon\Carbon::parse($sub->end_at)->format('d M Y') : '-' }}
                                                            </td>
                                                            <td>
                                                                @if($sub->status == 'active')
                                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                                @elseif($sub->status == 'expired')
                                                                    <span class="badge bg-danger">{{ __('Expired') }}</span>
                                                                @elseif($sub->status == 'cancelled')
                                                                    <span
                                                                        class="badge bg-warning text-dark">{{ __('Cancelled') }}</span>
                                                                @else
                                                                    <span class="badge bg-secondary">{{ __($sub->status) }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            {{ __('No subscription history found.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


@endsection
                        @push('scripts')

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const grid = document.querySelector('.order-grid');
                                    if (!grid) return;

                                    // Template for server print route with placeholder; will be replaced with order id if print url missing
                                    const orderPrintTemplate = '{{ route("order.print", "__ID__") }}';

                                    // create panel container
                                    const panel = document.createElement('div');
                                    panel.id = 'order-panel';
                                    panel.style.display = 'none';
                                    panel.className = 'mb-4';
                                    grid.parentNode.insertBefore(panel, grid);

                                    function renderDetail(order) {
                                        // Render each item with name, qty, unit price and line total
                                        const itemsArr = (order.items || []).map(it => {
                                            const unit = parseFloat(it.price) || 0;
                                            const qty = parseInt(it.qty) || 1;
                                            const lineTotal = (unit * qty);
                                            return { name: it.name || 'Item', qty: qty, unit: unit, image: it.image || null, lineTotal };
                                        });

                                        const currencySym = '{{ currencySymbol()[currency()] }}';
                                        const placeholderImg = '{{ asset("new-design/images/placeholder.png") }}';
                                        const itemsHtml = itemsArr.map(it => `
                                <div style="display:flex;align-items:center;gap:16px;padding:12px 0;border-bottom:1px solid #eee;">
                                <img src="${it.image || placeholderImg}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;"/>
                                <div style="flex:1">
                                    <div style="font-weight:700">${it.name}</div>
                                    <div style="color:#7b7b7b;font-size:13px">${it.qty} items × ${it.unit.toFixed(3)} ${currencySym}</div>
                                </div>
                                <div style="font-weight:700">${it.lineTotal.toFixed(3)} ${currencySym}</div>
                                </div>
                                `).join('');

                                        const subtotal = (typeof order.subtotal !== 'undefined' ? parseFloat(order.subtotal) : itemsArr.reduce((s, it) => s + it.lineTotal, 0)).toFixed(3);
                                        const tax = (typeof order.tax !== 'undefined' ? parseFloat(order.tax) : 0).toFixed(3);
                                        const delivery = (typeof order.delivery !== 'undefined' ? parseFloat(order.delivery) : 0).toFixed(3);
                                        const grandTotal = (typeof order.grand_total !== 'undefined' ? parseFloat(order.grand_total) : (parseFloat(subtotal) + parseFloat(tax) + parseFloat(delivery))).toFixed(3);

                                        // Prefer formatted strings provided by server (they include currency symbol/position)
                                        const subtotalFormatted = order.subtotal_formatted || (subtotal + ' ' + currencySym);
                                        const taxFormatted = order.tax_formatted || (tax + ' ' + currencySym);
                                        const deliveryFormatted = order.delivery_formatted || (delivery + ' ' + currencySym);
                                        const grandFormatted = order.grand_formatted || (grandTotal + ' ' + currencySym);

                                        panel.innerHTML = `
                                <div style="border:1px dashed #2a6b2a;padding:24px;border-radius:12px;background:#fff;">
                                <div class="order-panel-header">
                                    <div>
                                        <div style="font-size:20px;font-weight:800">{{ __('Order No:') }} <span style="font-weight:600;color:#777">#${order.order_number}</span></div>
                                        <div style="color:#7b7b7b;margin-top:6px">${order.created_at || ''}</div>
                                    </div>
                                    <div>
                                        <button id="back-to-orders" class="btn-view" style="margin-right:12px">{{ __('Back to Orders') }}</button>
                                        <button class="btn-track">{{ __('Track Order') }}</button>
                                    </div>
                                </div>
                                <div style="margin:12px 0">${itemsHtml}</div>
                                <div style="margin-top:16px;display:flex;justify-content:flex-end;gap:24px;align-items:center;flex-direction:column;">
                                    <div style="width:100%;max-width:420px;border-top:1px solid #eee;padding-top:12px;">
                                        <div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Subtotal') }}</div><div>${subtotalFormatted}</div></div>
                                        ${order.tax > 0 ? `<div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Tax') }}</div><div>${taxFormatted}</div></div>` : ''}
                                        <div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Delivery') }}</div><div>${deliveryFormatted}</div></div>
                                        <div style="display:flex;justify-content:space-between;font-weight:800;margin-top:8px"><div>{{ __('Total') }}</div><div>${grandFormatted}</div></div>
                                    </div>
                                </div>
                                </div>
                                `;
                                        panel.style.display = '';
                                        grid.style.display = 'none';
                                        var backDetailBtn = document.getElementById('back-to-orders');
                                        if (backDetailBtn) backDetailBtn.addEventListener('click', function () { panel.style.display = 'none'; grid.style.display = 'flex'; });
                                    }

                                    function renderTrack(order) {
                                        // Build a four-step progress bar: Confirmed -> Processing -> Shipped -> Delivered
                                        const steps = [
                                            { key: 'confirmed', label: '{{ __("Confirmed") }}' },
                                            { key: 'processing', label: '{{ __("Processing") }}' },
                                            { key: 'shipped', label: '{{ __("Shipped") }}' },
                                            { key: 'delivered', label: '{{ __("Orders delivered") }}' }
                                        ];

                                        const statusKey = order.stage || 'confirmed';
                                        let activeIndex = 0;
                                        if (statusKey === 'processing') activeIndex = 1;
                                        if (statusKey === 'shipped') activeIndex = 2;
                                        if (statusKey === 'delivered') activeIndex = 3;
                                        if (statusKey === 'cancelled') activeIndex = -1;

                                        // RTL support: keep the logical steps order and allow RTL painting to
                                        // display them from right to left. This ensures the first visual
                                        // stage on the right is always 'Confirmed' in Arabic while keeping
                                        // numbering stable. Progress percent is computed from the logical
                                        // active index and anchored to the correct side.
                                        const isRTL = (document && document.documentElement && document.documentElement.dir === 'rtl');
                                        const displaySteps = steps; // do not reverse; RTL will render DOM from right
                                        const displayActiveIndex = activeIndex; // logical index
                                        const progressPosition = isRTL ? 'right:0;' : 'left:0;';
                                        const percent = (activeIndex > -1) ? Math.round((activeIndex / (steps.length - 1)) * 100) : 0;

                                        const stepDots = displaySteps.map((s, idx) => {
                                            const active = idx <= displayActiveIndex && displayActiveIndex !== -1;
                                            const color = active ? '#ff7a00' : '#e6e6e6';
                                            // choose a timestamp to show under each step when available using step key
                                            let timeLabel = '';
                                            if (s.key === 'confirmed') timeLabel = order.created_at || order.confirmed_at || '';
                                            if (s.key === 'processing') timeLabel = order.processing_at || order.confirmed_at || '';
                                            if (s.key === 'shipped') timeLabel = order.shipped_at || '';
                                            if (s.key === 'delivered') timeLabel = order.delivered_at || '';
                                            // compute number label according to original steps order so 'confirmed' is always 1
                                            const originalIndex = steps.findIndex(st => st.key === s.key);
                                            const numberLabel = originalIndex >= 0 ? (originalIndex + 1) : (idx + 1);
                                            return `
                                <div style="flex:1;text-align:center;">
                                    <div style="width:40px;height:40px;margin:0 auto;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">${numberLabel}</div>
                                    <div style="margin-top:8px;font-weight:700;color:${active ? '#ff7a00' : '#7b7b7b'}">${s.label}</div>
                                </div>
                                `;
                                        }).join('');
                                        // Build items HTML (reuse logic from renderDetail)
                                        const itemsArr = (order.items || []).map(it => {
                                            const unit = parseFloat(it.price) || 0;
                                            const qty = parseInt(it.qty) || 1;
                                            const lineTotal = (unit * qty);
                                            return { name: it.name || 'Item', qty: qty, unit: unit, image: it.image || null, lineTotal };
                                        });
                                        const currencySym = '{{ currencySymbol()[currency()] }}';
                                        const placeholderImg = '{{ asset("new-design/images/placeholder.png") }}';
                                        const itemsHtml = itemsArr.map(it => `
                                <div style="display:flex;align-items:center;gap:16px;padding:12px 0;border-bottom:1px solid #eee;">
                                <img src="${it.image || placeholderImg}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;"/>
                                <div style="flex:1">
                                    <div style="font-weight:700">${it.name}</div>
                                    <div style="color:#7b7b7b;font-size:13px">${it.qty} {{ __('items') }} × ${it.unit.toFixed(3)} ${currencySym}</div>
                                </div>
                                <div style="font-weight:700">${it.lineTotal.toFixed(3)} ${currencySym}</div>
                                </div>
                                `).join('');

                                        const subtotal = (typeof order.subtotal !== 'undefined' ? parseFloat(order.subtotal) : itemsArr.reduce((s, it) => s + it.lineTotal, 0)).toFixed(3);
                                        const tax = (typeof order.tax !== 'undefined' ? parseFloat(order.tax) : 0).toFixed(3);
                                        const delivery = (typeof order.delivery !== 'undefined' ? parseFloat(order.delivery) : 0).toFixed(3);
                                        const grandTotal = (typeof order.grand_total !== 'undefined' ? parseFloat(order.grand_total) : (parseFloat(subtotal) + parseFloat(tax) + parseFloat(delivery))).toFixed(3);
                                        const subtotalFormatted = order.subtotal_formatted || (subtotal + ' ' + currencySym);
                                        const taxFormatted = order.tax_formatted || (tax + ' ' + currencySym);
                                        const deliveryFormatted = order.delivery_formatted || (delivery + ' ' + currencySym);
                                        const grandFormatted = order.grand_formatted || (grandTotal + ' ' + currencySym);

                                        const deliveryName = order.delivery_name || order.delivery_contact_name || '{{ $user->name ?? "" }}';
                                        const deliveryPhone = order.delivery_phone || order.delivery_contact || '';

                                        panel.innerHTML = `
                                <div style="margin-bottom:16px;">
                                <button id="back-to-orders-track" class="btn-view" style="background:transparent;border:0;color:#2a6b2a;font-weight:700;padding:8px 0;display:inline-flex;align-items:center;gap:8px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                    {{ __('Back to Orders') }}
                                </button>
                                </div>
                                <div style="border:1px dashed #2a6b2a;padding:24px;border-radius:12px;background:#fff;">
                                <div class="order-panel-header">
                                    <div>
                                        <div style="font-size:20px;font-weight:800">{{ __('Order No:') }} <span style="font-weight:600;color:#777">#${order.order_number}</span></div>
                                        <div style="color:#7b7b7b;margin-top:6px">${order.created_at || ''}</div>
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:center;">
                                        <a href="#" class="btn-view invoice-link" style="background:#f3f7ee;border:1px solid #e6f0d8;padding:8px 12px;border-radius:8px;color:#2a6b2a;margin-right:8px">{{ __('Invoice') }}</a>
                                        <button class="btn-track" style="background:#ff8a00;color:#fff;border-radius:8px;padding:8px 12px">{{ __('Track Order') }}</button>
                                    </div>
                                </div>

                                <div style="padding:18px 12px">
                                    <div style="height:8px;background:#eee;border-radius:8px;position:relative;margin-bottom:18px;">
                                        <div style="position:absolute;${progressPosition}top:0;height:100%;width:${percent}%;background:#ff7a00;border-radius:8px;transition:width .6s ease;"></div>
                                    </div>
                                    <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:14px">${stepDots}</div>
                                </div>

                                <div style="margin:12px 0">${itemsHtml}</div>

                                <div style="margin-top:16px;display:flex;justify-content:flex-end;gap:24px;align-items:center;flex-direction:column;">
                                    <div style="width:100%;max-width:420px;border-top:1px solid #eee;padding-top:12px;">
                                        <div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Subtotal') }}</div><div>${subtotalFormatted}</div></div>
                                        ${order.tax > 0 ? `<div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Tax') }}</div><div>${taxFormatted}</div></div>` : ''}
                                        <div style="display:flex;justify-content:space-between;color:#7b7b7b"><div>{{ __('Delivery') }}</div><div>${deliveryFormatted}</div></div>
                                        <div style="display:flex;justify-content:space-between;font-weight:800;margin-top:8px"><div>{{ __('Total') }}</div><div>${grandFormatted}</div></div>
                                    </div>
                                </div>
                                </div>
                                `;
                                        panel.style.display = '';
                                        grid.style.display = 'none';
                                        // attach invoice click handler to open print url (fallback to server route template)
                                        (function () {
                                            const inv = panel.querySelector('.invoice-link');
                                            if (inv) {
                                                inv.addEventListener('click', function (ev) {
                                                    ev.preventDefault();
                                                    const url = (order.print_url && order.print_url.length) ? order.print_url : orderPrintTemplate.replace('__ID__', order.id);
                                                    try { window.open(url, '_blank'); } catch (err) { window.location.href = url; }
                                                });
                                            }
                                        })();
                                        // Attach back button listeners safely (element may or may not exist)
                                        var backBtn = document.getElementById('back-to-orders');
                                        if (backBtn) backBtn.addEventListener('click', function () { panel.style.display = 'none'; grid.style.display = 'flex'; });
                                        var backTrackBtn = document.getElementById('back-to-orders-track');
                                        if (backTrackBtn) backTrackBtn.addEventListener('click', function () { panel.style.display = 'none'; grid.style.display = 'flex'; });
                                    }

                                    grid.addEventListener('click', function (e) {
                                        const btn = e.target.closest('button');
                                        if (!btn) return;
                                        const action = btn.getAttribute('data-action');
                                        const card = btn.closest('.order-card');
                                        if (!card) return;

                                        // build order object from data attributes to avoid JSON attribute encoding issues
                                        const itemsRaw = card.getAttribute('data-items');
                                        let items = [];
                                        try { items = itemsRaw ? JSON.parse(itemsRaw) : []; } catch (err) { items = []; }

                                        const order = {
                                            id: card.getAttribute('data-id') || '',
                                            order_number: card.getAttribute('data-order-number') || '',
                                            stage: card.getAttribute('data-stage') || 'confirmed',
                                            created_at: card.getAttribute('data-created') || '',
                                            item_count: parseInt(card.getAttribute('data-item-count') || items.length || 0, 10),
                                            subtotal: parseFloat(card.getAttribute('data-subtotal') || 0),
                                            tax: parseFloat(card.getAttribute('data-tax') || 0),
                                            delivery: parseFloat(card.getAttribute('data-delivery') || 0),
                                            grand_total: parseFloat(card.getAttribute('data-grand') || 0),
                                            // formatted strings provided by server (include currency symbol/position)
                                            subtotal_formatted: card.getAttribute('data-subtotal-formatted') || null,
                                            tax_formatted: card.getAttribute('data-tax-formatted') || null,
                                            delivery_formatted: card.getAttribute('data-delivery-formatted') || null,
                                            grand_formatted: card.getAttribute('data-grand-formatted') || null,
                                            // additional metadata
                                            print_url: card.getAttribute('data-print-url') || '',
                                            delivery_name: card.getAttribute('data-delivery-name') || '',
                                            delivery_phone: card.getAttribute('data-delivery-phone') || '',
                                            confirmed_at: card.getAttribute('data-confirmed-at') || '',
                                            processing_at: card.getAttribute('data-processing-at') || '',
                                            shipped_at: card.getAttribute('data-shipped-at') || '',
                                            delivered_at: card.getAttribute('data-delivered-at') || '',
                                            items: items
                                        };

                                        if (action === 'view') {
                                            renderDetail(order);
                                        } else if (action === 'track') {
                                            renderTrack(order);
                                        }
                                    });

                                    // Address tab interactions
                                    const addressGrid = document.querySelector('.address-list');
                                    const addNewBtn = document.getElementById('add-new-address');
                                    let addressPanel = null;
                                    const csrfToken = '{{ csrf_token() }}';
                                    const addressesBaseUrl = '{{ url("user/addresses") }}';

                                    function createAddressPanel() {
                                        if (addressPanel) return addressPanel;
                                        addressPanel = document.createElement('div');
                                        addressPanel.id = 'address-panel';
                                        addressPanel.style.display = 'none';
                                        addressPanel.className = 'mb-4';
                                        const addressSection = document.querySelector('.address-grid');
                                        if (!addressSection) {
                                            // fallback: append to body if expected container not found
                                            document.body.appendChild(addressPanel);
                                            return addressPanel;
                                        }
                                        // insert the panel as a child of .address-grid before the .address-list
                                        const listChild = addressSection.querySelector('.address-list');
                                        if (listChild && listChild.parentNode === addressSection) {
                                            addressSection.insertBefore(addressPanel, listChild);
                                        } else {
                                            // safe fallback: append to addressSection
                                            addressSection.appendChild(addressPanel);
                                        }
                                        return addressPanel;
                                    }

                                    function renderAddressForm(data) {
                                        const panel = createAddressPanel();
                                        panel.innerHTML = `
                                <div class="address-form-panel">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                                    <div style="font-weight:700;display:flex;align-items:center;gap:10px"><div class="address-pin">📍</div><div>Address Information</div></div>
                                    <div>
                                        <button id="back-to-addresses" class="btn-view" style="background:transparent;border:0;color:#2a6b2a;font-weight:700">Back</button>
                                    </div>
                                </div>

                                <form id="address-form">
                                    <input type="hidden" name="id" value="${data?.id || ''}" />

                                    <div class="address-form-row address-top-row" style="margin-bottom:12px;">
                                        <div class="col col-half"><label class="form-label">{{ __('profile.full_name_required') }}*</label><input class="address-input" name="recipient_name" value="${(data && data.recipient_name) || ''}" /></div>
                                        <div class="col col-half"><label class="form-label">{{ __('profile.address_title_required') }}*</label><input class="address-input" name="label" value="${data?.label || ''}" /></div>
                                    </div>

                                    <div style="margin-bottom:12px;"><label class="form-label">{{ __('profile.address_required') }}*</label><textarea class="address-textarea" name="address_line1">${data?.address_line1 || ''}</textarea></div>

                                    <div class="address-small-grid" style="margin-bottom:12px;">
                                        <input class="address-input" placeholder="{{ __('street number and name')}}" name="address_line2" value="${data?.address_line2 || ''}" />
                                        <input class="address-input" placeholder="{{ __('building name')}}" name="building" value="${data?.building || ''}" />
                                        <input class="address-input" placeholder="{{ __('apartment, house number')}}" name="apartment" value="${data?.apartment || ''}" />
                                        <input class="address-input" placeholder="{{ __('address specific instructions')}}" name="instructions" value="${data?.instructions || ''}" />
                                    </div>

                                    <div style="display:flex;gap:12px;align-items:center;justify-content:flex-start;margin-bottom:6px;">
                                        <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="is_default" ${data?.is_default ? 'checked' : ''}/> {{ __('Set as default') }}</label>
                                    </div>

                                    <div style="text-align:center;margin-top:6px;"><button class="btn-save-address" type="submit">{{ __('SAVED ADDRESS') }}</button></div>
                                </form>
                                </div>
                                `;
                                        panel.style.display = '';
                                        // hide list and header add button while adding
                                        const addBtn = document.getElementById('add-new-address');
                                        if (addBtn) addBtn.style.display = 'none';
                                        const listEl = document.querySelector('.address-list');
                                        if (listEl) listEl.style.display = 'none';

                                        document.getElementById('back-to-addresses').addEventListener('click', function () {
                                            panel.style.display = 'none';
                                            if (addBtn) addBtn.style.display = '';
                                            if (listEl) listEl.style.display = '';
                                        });

                                        // form submit handler -> POST/PUT to backend
                                        panel.querySelector('#address-form').addEventListener('submit', async function (ev) {
                                            ev.preventDefault();
                                            const formEl = ev.target;
                                            const form = new FormData(formEl);
                                            const payload = {};
                                            form.forEach((v, k) => { payload[k] = v; });
                                            payload.is_default = form.get('is_default') ? 1 : 0;

                                            try {
                                                let res;
                                                if (payload.id) {
                                                    // update
                                                    res = await fetch(addressesBaseUrl + '/' + payload.id, {
                                                        method: 'PUT',
                                                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                                                        body: JSON.stringify(payload)
                                                    });
                                                } else {
                                                    res = await fetch(addressesBaseUrl, {
                                                        method: 'POST',
                                                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                                                        body: JSON.stringify(payload)
                                                    });
                                                }
                                                const data = await res.json();
                                                if (!res.ok) {
                                                    alert((data.message || 'Validation error') + '\n' + JSON.stringify(data.errors || {}));
                                                    return;
                                                }

                                                const addr = data.address;
                                                // append or replace card
                                                const list = document.querySelector('.address-list');
                                                if (payload.id) {
                                                    // find and replace existing card
                                                    const existing = list.querySelector(`.address-card[data-address*='"id":${addr.id}']`);
                                                    if (existing) existing.remove();
                                                }
                                                const card = document.createElement('div');
                                                card.className = 'address-card';
                                                card.setAttribute('data-address', JSON.stringify(addr));
                                                card.innerHTML = `<div class="address-icon">📍</div><div class="address-body"><div class="address-title">${addr.label || addr.recipient_name || 'Home'} ${addr.is_default ? '<span class="badge bg-success ms-2">Default</span>' : ''} <span class="text-muted">${addr.city || ''}</span></div><div class="address-line">${addr.address_line1}${addr.address_line2 ? (', ' + addr.address_line2) : ''}</div><div class="address-line small text-muted">${addr.phone || ''}${addr.postal_code ? (' · ' + addr.postal_code) : ''}</div></div><div style="display:flex;flex-direction:column;gap:8px"><button class="btn btn-outline-secondary btn-sm" type="button" data-action="edit">{{ __('Edit') }}</button><button class="btn btn-outline-danger btn-sm" type="button" data-action="delete">🗑</button>${addr.is_default ? '' : '<button class="btn btn-outline-primary btn-sm" type="button" data-action="set-default">Set Default</button>'}</div>`;
                                                if (list) list.prepend(card);

                                                panel.style.display = 'none';
                                                if (addBtn) addBtn.style.display = '';
                                                if (listEl) listEl.style.display = '';
                                            } catch (err) {
                                                console.error(err);
                                                alert('Failed to save address');
                                            }
                                        });
                                    }

                                    if (addNewBtn) {
                                        addNewBtn.addEventListener('click', function () { renderAddressForm(); });
                                    }

                                    // handle edit / delete / set-default on address list (delegated)
                                    if (addressGrid) {
                                        addressGrid.addEventListener('click', async function (e) {
                                            const btn = e.target.closest('button');
                                            if (!btn) return;
                                            const action = btn.getAttribute('data-action');
                                            const card = btn.closest('.address-card');
                                            if (!card) return;
                                            let addr;
                                            try { addr = JSON.parse(card.getAttribute('data-address')); } catch (err) { addr = null; }

                                            if (action === 'delete') {
                                                if (!addr || !addr.id) { card.remove(); return; }
                                                if (!confirm('{{ __('Are you sure you want to delete this address?') }}')) return;
                                                try {
                                                    const res = await fetch(addressesBaseUrl + '/' + addr.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                                                    if (res.ok) card.remove(); else alert('Failed to delete');
                                                } catch (err) { console.error(err); alert('Failed to delete'); }
                                            }

                                            if (action === 'edit') {
                                                renderAddressForm(addr || {});
                                            }

                                            if (action === 'set-default') {
                                                if (!addr || !addr.id) return;
                                                try {
                                                    const res = await fetch(addressesBaseUrl + '/' + addr.id + '/default', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                                                    if (res.ok) {
                                                        // simple refresh: reload page so default badge moves (keep simple)
                                                        location.reload();
                                                    } else {
                                                        alert('Failed to set default');
                                                    }
                                                } catch (err) { console.error(err); alert('Failed to set default'); }
                                            }
                                        });
                                    }
                                    // Handle URL hash for tab switching
                                    if (window.location.hash) {
                                        const hash = window.location.hash;
                                        const targetTab = document.querySelector(`button[data-bs-target="${hash}"]`);
                                        if (targetTab) {
                                            // Use Bootstrap's Tab API to show the tab
                                            const tabTrigger = new bootstrap.Tab(targetTab);
                                            tabTrigger.show();
                                        }
                                    }
                                });
                            </script>
                        @endpush