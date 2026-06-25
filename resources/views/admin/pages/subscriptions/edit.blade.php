@extends('admin.master', ['menu' => 'subscriptions', 'submenu' => 'subscriptions'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Edit Subscription') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-vertical__item bg-style">
                <form method="post" action="{{ route('admin.subscriptions.update', $subscription->id) }}">
                    @csrf
                    <div class="input__group mb-25">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ $subscription->name }}" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Price') }}</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ $subscription->price }}">
                        <small class="text-info font-weight-bold d-block mt-2" style="font-size: 12px; color: #17a2b8; line-height: 1.5;">
                            💡 <strong>تنبيه:</strong> هذه الباقة تعمل بنظام العضوية المميزة (مثل Amazon Prime). قيمة الاشتراك يدفعها العميل مقابل الحصول على المزايا والخصومات، ولا تُضاف كرصيد في محفظته.
                            <br>
                            💡 <strong>Note:</strong> This package operates as a premium membership (like Amazon Prime). The subscription fee is paid for perks/discounts and is not added to the user's wallet.
                        </small>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Period Type') }}</label>
                        <select name="period_type" class="form-control">
                            <option value="days" {{ $subscription->period_type == 'days' ? 'selected' : '' }}>{{ __('Days') }}
                            </option>
                            <option value="months" {{ $subscription->period_type == 'months' ? 'selected' : '' }}>
                                {{ __('Months') }}</option>
                            <option value="years" {{ $subscription->period_type == 'years' ? 'selected' : '' }}>
                                {{ __('Years') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Period Value') }}</label>
                        <input type="number" name="period_value" class="form-control"
                            value="{{ $subscription->period_value }}" min="1">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Discount Percent') }}</label>
                        <input type="number" step="0.01" name="discount_percent" class="form-control"
                            value="{{ $subscription->discount_percent }}">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Max Discount Amount') }}</label>
                        <input type="number" step="0.01" name="max_discount_amount" class="form-control"
                            value="{{ $subscription->max_discount_amount }}">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Free Shipping') }}</label>
                        <select name="free_shipping" class="form-control">
                            <option value="0" {{ !$subscription->free_shipping ? 'selected' : '' }}>{{ __('No') }}</option>
                            <option value="1" {{ $subscription->free_shipping ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Tax Exempt') }}</label>
                        <select name="tax_exempt" class="form-control">
                            <option value="0" {{ !$subscription->tax_exempt ? 'selected' : '' }}>{{ __('No') }}</option>
                            <option value="1" {{ $subscription->tax_exempt ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description') }}</label>
                        <textarea name="description" class="form-control"
                            rows="4">{{ $subscription->description }}</textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Status') }}</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $subscription->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ !$subscription->is_active ? 'selected' : '' }}>{{ __('Inactive') }}
                            </option>
                        </select>
                    </div>
                    <div class="input__button">
                        <button type="submit" class="btn btn-blue">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection