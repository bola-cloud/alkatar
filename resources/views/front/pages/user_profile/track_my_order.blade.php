@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    <!-- breadcrumb area start here  -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-wrap text-center">
                <h2 class="page-title">{{ __('Track My Order') }}</h2>
                <ul class="breadcrumb-pages">
                    <li class="page-item"><a class="page-item-link" href="{{ route('front') }}">{{ __('Home') }}</a></li>
                    <li class="page-item">{{ __('Track My Order') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end here  -->

    <!-- Profile Page area start here  -->
    <div class="profile-page-area section">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    @include('front.layouts.include.user_profile_sidebar', ['menu' => 'order'])
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="user-profile-right-part">
                        <div class="user-profile-content-box my-order-page-box track-my-order-page-box">

                            <div class="order-tracking-card p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h3 class="mb-1">{{ __('Order ID') }}: <span class="fw-bold">{{ $order->Order_Number ?? $order->id }}</span></h3>
                                        <div class="small text-muted">{{ __('Order date') }}: {{ date('M d, Y', strtotime($order->created_at)) }}</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="{{ route('checkout.invoice', [$order->id]) }}" class="btn btn-outline-secondary btn-sm">{{ __('Invoice') }}</a>
                                        <a href="javascript:void(0)" class="btn btn-warning btn-sm text-white">{{ __('Track order') }}</a>
                                    </div>
                                </div>

                                {{-- Timeline --}}
                                @php
                                    $status = intval($order->Order_Status ?? 0);
                                @endphp
                                <div class="order-timeline mb-4">
                                    <div class="timeline-bar">
                                        <div class="timeline-progress" style="width: @if($status >= ORDER_DELIVERED) 100% @elseif($status >= ORDER_SHIPPED) 66% @elseif($status >= ORDER_PROCESSING) 33% @else 0% @endif"></div>
                                    </div>
                                    <div class="timeline-steps d-flex justify-content-between mt-3">
                                        <div class="text-center step @if($status >= ORDER_PENDING) active @endif">
                                            <div class="step-dot"></div>
                                            <div class="step-title">{{ __('Order Confirmed') }}</div>
                                            <div class="step-date small text-muted">{{ $order->created_at ? date('D, j M Y', strtotime($order->created_at)) : '' }}</div>
                                        </div>
                                        <div class="text-center step @if($status >= ORDER_SHIPPED) active @endif">
                                            <div class="step-dot"></div>
                                            <div class="step-title">{{ __('Shipped') }}</div>
                                            <div class="step-date small text-muted">@if($order->Delivery_At) {{ date('D, j M Y', strtotime($order->Delivery_At)) }} @endif</div>
                                        </div>
                                        <div class="text-center step @if($status == ORDER_DELIVERED) active @endif">
                                            <div class="step-dot"></div>
                                            <div class="step-title">{{ __('Delivered') }}</div>
                                            <div class="step-date small text-muted">@if($order->Delivery_At && $status==ORDER_DELIVERED) {{ date('D, j M Y', strtotime($order->Delivery_At)) }} @endif</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Items list similar to design --}}
                                <div class="order-items-list">
                                    @foreach ($order->order_details as $od)
                                        <div class="order-item d-flex align-items-start py-3 border-bottom">
                                            <div class="me-3 item-thumb">
                                                <img src="{{ asset(ProductImage() . $od->product->Primary_Image) }}" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:8px;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ langConverter($od->product->en_Product_Name, $od->product->fr_Product_Name) }}</div>
                                                <div class="small text-muted">{{ $od->product->Brand ?? '' }}</div>
                                                <div class="small text-muted">{{ $od->product->category->name ?? '' }}</div>
                                                <div class="small text-muted">{{ $od->Quantity }} {{ __('items') }}</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-semibold">{{ currencyConverter($od->Price) }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Delivery info box --}}
                                <div class="delivery-info mt-4 p-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-muted">{{ __('Delivery info') }}</div>
                                        <div class="fw-bold">{{ $order->shipping_name ?? ($order->user->name ?? '') }}</div>
                                        <div class="small text-muted">{{ $order->shipping_phone ?? ($order->user->phone ?? '') }}</div>
                                    </div>
                                    <div>
                                        <a href="tel:{{ $order->shipping_phone ?? ($order->user->phone ?? '') }}" class="btn btn-success btn-sm text-white">{{ __('Call') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="order-table mt-5">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Item') }}</th>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <th>{{ __('Review') }}</th>
                                                @endif
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Qty') }}</th>
                                                <th>{{ __('Subtotal') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->order_details as $od)
                                                <tr>
                                                    <td>
                                                        {{ langConverter($od->product->en_Product_Name, $od->product->fr_Product_Name) }}
                                                    </td>
                                                    <td>
                                                        <div class="item-image-lsit d-flex align-items-center">
                                                            <div class="single-item">
                                                                <img class="order-image"
                                                                    src="{{ asset(ProductImage() . $od->product->Primary_Image) }}"
                                                                    alt="images">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @if ($order->Order_Status == ORDER_DELIVERED)
                                                        @if (hasPerviousReview($od->Product_Id) == 1)
                                                            <td><button class="primary-btn-v2 write-review-btn" disabled>
                                                                    {{ __('Reviewed') }}</button></td>
                                                        @else
                                                            <td><button class="primary-btn write-review-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#writeReviewModal{{ $od->id }}">
                                                                    {{ __('Review') }}</button></td>
                                                        @endif
                                                    @endif
                                                    <td>
                                                        <span class="amount">{{ currencyConverter($od->Price) }}</span>
                                                    </td>
                                                    <td>{{ $od->Quantity }}</td>
                                                    <td>{{ currencyConverter($od->Total_Price) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td>{{ __('Subtotal') }}</td>
                                                <td>{{ currencyConverter($order->Sub_Total) }}</td>
                                            </tr>
                                            @if($order->Tax > 0)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td>{{ __('Tax') }}</td>
                                                <td>{{ currencyConverter($order->Tax) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td>{{ __('Delivery Charge') }}</td>
                                                <td>{{ currencyConverter($order->Delivery_Charge) }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td>{{ __('Discount (-)') }}</td>
                                                <td>{{ currencyConverter($order->Coupon_Amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                @if ($order->Order_Status == ORDER_DELIVERED)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td>{{ __('Grand Total') }}</td>
                                                <td>{{ currencyConverter($order->Grand_Total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Page area end here  -->

    <!-- Write Review Modal Start -->
    @if ($order->Order_Status == ORDER_DELIVERED)
        @foreach ($order->order_details as $domd)
            <div class="modal fade writeReviewModal" id="writeReviewModal{{ $domd->id }}" tabindex="-1"
                aria-labelledby="writeReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-5">
                        <div class="modal-header">
                            <h2 class="modal-title fw-bold text-black" id="writeReviewModalLabel">
                                {{ __('Write Your Feedback') }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="feedback" method="POST" action="{{ route('user.profile.review_store') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $domd->Product_Id }}">
                                <div class="mb-3  w-100">
                                    <label for="exampleFormControlTextarea1"
                                        class="form-label">{{ __('Rating') }}</label><br>
                                    <select class="form-select form-control" aria-label="Default select example"
                                        name="rating">
                                        <option selected>{{ __('Select') }}</option>
                                        <option value="5">5</option>
                                        <option value="4">4</option>
                                        <option value="3">3</option>
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1"
                                        class="form-label">{{ __('Write Your Feedback') }}</label>
                                    <textarea id="exampleFormControlTextarea1" rows="3" name="feedback"></textarea>
                                </div>

                                <button type="submit" class="primary-btn !bg-primary-red !text-white">{{ __('Submit') }}</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    <!-- Write Review Modal End -->
@push('post_css')
<style>
    .order-tracking-card{ border:2px dashed #ced9ce; border-radius:12px; background:#fff }
    .order-tracking-card .fw-bold{ color:#23332b }
    .timeline-bar{ height:6px; background:#e9e9e9; border-radius:6px; overflow:hidden }
    .timeline-progress{ height:100%; background:#ff8a00; width:0; transition:width .3s }
    .timeline-steps .step{ width:33%; }
    .timeline-steps .step .step-dot{ width:14px; height:14px; border-radius:50%; background:#e9e9e9; margin:0 auto 8px }
    .timeline-steps .step.active .step-dot{ background:#ff8a00 }
    .timeline-steps .step .step-title{ font-weight:600; color:#f08a00 }
    .order-item .item-thumb img{ border-radius:8px }
    .delivery-info{ background:#f6f9f6; border-radius:8px }
    .order-tracking-card .btn-warning{ background:#ff8a00; border-color:#ff8a00 }
    @media (max-width:767px){ .timeline-steps .step .step-title { font-size:13px } }
</style>
@endpush

@endsection
