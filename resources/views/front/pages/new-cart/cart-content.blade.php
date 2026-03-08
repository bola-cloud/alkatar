@extends('front.layouts.new_design_layout')
@section('title','Cart')
@section('content')

    {{-- banner included right after the navbar so it appears as part of header --}}
    @include('front.partials.category_banner', ['title' => __('Your cart')])

    <div class="container py-5">
        <div class="row">
            <div class="col-12 mb-4">
                <a href="{{ url()->previous() }}" class="d-inline-flex align-items-center text-decoration-none text-dark mb-3">
                    <i class="bi bi-arrow-left me-2"></i>
                    {{ __('Back to menu') }}
                </a>
                <h1 class="h3">{{ __('Your cart') }}</h1>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">
                    @php $total = 0; @endphp
                    @forelse($content as $item)
                        <div class="card shadow-sm border-0">
                            <div class="card-body d-flex align-items-center cart-card">
                                <div class="me-4">
                                    <a href="{{ route('single.product', $item->options->slug ?? '') }}">
                                        <img src="{{ asset(ProductImage() . $item->options->image) }}" alt="{{ langConverter($item->name, $item->options->name_ar) }}" style="width:96px;height:96px;object-fit:cover;" class="rounded">
                                    </a>
                                </div>

                                <div class="flex-grow-1">
                                    <h3 class="h6 mb-1"><a href="{{ route('single.product', $item->options->slug ?? '') }}" class="text-dark">{{ langConverter($item->name, $item->options->name_ar) }}</a></h3>
                                    @if($item->options->color)
                                        <div class="small mb-1">{{ __('Color') }}: <span style="display:inline-block;width:14px;height:14px;background:{{ $item->options->color }};border-radius:3px;margin-left:6px;vertical-align:middle;"></span></div>
                                    @endif

                                    @if($item->options->size)
                                        <div class="small">{{ __('Option') }}: {{ langConverter($item->options->size, $item->options->size_ar) }}</div>
                                    @endif

                                    @if($item->options->weight)
                                        <div class="small">{{ __('Size') }}: {{ $item->options->weight->weight }} {{ __('Grams') }}</div>
                                    @endif

                                    @if($item->options->additions)
                                        <div class="small mt-2">{{ __('Additions') }}:
                                            @foreach($item->options->additions as $addition)
                                                <span class="badge bg-light text-dark me-1">{{ langConverter($addition->name, $addition->name_ar) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="text-end ms-3" style="min-width:160px;">
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-danger deleteItemCart" data-id="{{ $item->rowId }}" title="{{ __('Delete Item') }}"><i class="bi bi-x-lg"></i></button>
                                    </div>


                                    <div class="fw-bold">{{ currencyConverter($item->price) }}</div>
                                    <div class="text-muted small">{{ __('Total') }}: <span class="SubTotalAmount">{{ currencyConverter($item->subtotal) }}</span></div>

                                    <div class="mt-3">
                                        <div class="d-inline-flex align-items-center border rounded px-2">
                                            <button class="btn btn-sm btn-link text-decoration-none qty_decrease" data-id="{{ $item->rowId }}">-</button>
                                            <input type="text" class="form-control form-control-sm qty_value text-center mx-1" value="{{ $item->qty }}" style="width:48px;" readonly>
                                            <button class="btn btn-sm btn-link text-decoration-none qty_increase" data-id="{{ $item->rowId }}">+</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <h5>{{ __('Your cart is empty') }}</h5>
                                <p class="text-muted">{{ __('Your cart is empty. Please go to your home page for listing it.') }}</p>
                            </div>
                        </div>
                    @endforelse

                    {{-- Coupon box similar to old design --}}
                    <div class="card border-0">
                        <div class="card-body">
                            <form action="{{ route('apply.coupon') }}" method="post" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="coupon_code" class="form-control" placeholder="{{ __('Coupon Code') }}">

                                @if(auth()->user() && auth()->user()->is_admin)
                                    <select id="user_id" name="user_id" class="form-control select2 ms-2" style="max-width:180px;">
                                        <option value="">{{ __('No User Coupon') }}</option>
                                        @foreach($users ?? [] as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->number }})</option>
                                        @endforeach
                                    </select>
                                @endif

                                <button class="btn btn-primary">{{ __('Apply Coupon') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top:100px;">
                    <div class="p-4" style="border:2px solid #d8eec3;border-radius:10px;background:#fff;">
                        <h5 class="card-title" style="color:#ff9a00;font-weight:700;margin-bottom:8px;">{{ __('Order summary') }}</h5>
                        <hr style="border-top:1px solid #e6e6e6;margin:8px 0 16px 0;">

                        {{-- Hidden data for JS --}}
                        <div id="minOrderData" 
                             data-amount="{{ floatval(allsetting('min_order_amount') ?: 3.990) }}"
                             data-checkout-url="{{ route('checkout') }}"
                             data-msg="{{ __('Minimum order amount is :amount OMR.', ['amount' => number_format(floatval(allsetting('min_order_amount') ?: 3.990), 3)]) }}"
                             style="display:none;"></div>

                        <div class="d-flex justify-content-between mb-2">
                            <div class="text-muted" style="color:#6b7a6b;">{{ __('Subtotal:') }}</div>
                            <div class="fw-bold totalAmount" style="color:#0b0b0b;">{{ currencyConverter(subtotal()) }}</div>
                        </div>

                        {{-- expose coupon amount to JS so client can update final total dynamically --}}
                        <div id="CartCouponAmount" data-amount="{{ Session::get('CouponAmount', 0) }}" style="display:none;"></div>

                        @if (session()->has('CouponAmount'))
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-muted" style="color:#6b7a6b;">{{ __('Coupon Discount') }}</div>
                                <div class="fw-bold" style="color:#0b0b0b;">{{ currencyConverter(Session::get('CouponAmount')) }}</div>
                            </div>
                        @endif

                        @php
                            $coupon = Session::get('CouponAmount') ?? 0;
                            $all_total = floatval(subtotal()) - floatval($coupon);
                        @endphp

                        <div style="height:12px"></div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div style="font-weight:600;color:#2f6b3a;">{{ __('TOTAL:') }}</div>
                            <div class="fw-bold cart-page-final-total" style="color:#2f6b3a;font-size:18px;">{{ currencyConverter($all_total) }}</div>
                        </div>

                        @php
                            $min_order_amount = floatval(allsetting('min_order_amount') ?: 3.990);
                        @endphp

                        <div id="minOrderWarning" class="alert alert-warning mb-3 {{ subtotal() < $min_order_amount ? '' : 'd-none' }}" style="font-size: 14px; border-radius: 10px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ __('Minimum order amount is :amount OMR.', ['amount' => number_format($min_order_amount, 3)]) }}
                            <br>
                            <small>{{ __('Please add more items to proceed.') }}</small>
                        </div>

                        <a href="{{ subtotal() < $min_order_amount ? 'javascript:void(0)' : route('checkout') }}" 
                           id="checkoutBtn"
                           class="btn w-100 {{ subtotal() < $min_order_amount ? 'disabled' : '' }}" 
                           style="background:{{ subtotal() < $min_order_amount ? '#cccccc' : '#b6bf21' }};color:#ffffff;border-radius:28px;padding:12px 20px;font-weight:600;"
                           @if(subtotal() < $min_order_amount) onclick="toastr.error('{{ __('Minimum order amount is :amount OMR.', ['amount' => number_format($min_order_amount, 3)]) }}')" @endif>
                           {{ __('Checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="CartDeleteFromSession" data-url="{{ route('cart.delete') }}"></div>
    <div id="CartIncrementFromSession" data-url="{{ route('cart.increase') }}"></div>
    <div id="CartDecrementFromSession" data-url="{{ route('cart.decrease') }}"></div>

@endsection

@push('post_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script src="{{ asset('frontend/assets/js/pages/cart.js') }}"></script>
@endpush
