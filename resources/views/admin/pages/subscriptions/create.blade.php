@extends('admin.master', ['menu' => 'subscriptions', 'submenu' => 'subscriptions'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Create Subscription') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-vertical__item bg-style">
                <form method="post" action="{{ route('admin.subscriptions.store') }}">
                    @csrf
                    <div class="input__group mb-25">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Price') }}</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="0">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Period Type') }}</label>
                        <select name="period_type" class="form-control">
                            <option value="days">{{ __('Days') }}</option>
                            <option value="months" selected>{{ __('Months') }}</option>
                            <option value="years">{{ __('Years') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Period Value') }}</label>
                        <input type="number" name="period_value" class="form-control" value="1" min="1">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Discount Percent') }}</label>
                        <input type="number" step="0.01" name="discount_percent" class="form-control">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Max Discount Amount') }}</label>
                        <input type="number" step="0.01" name="max_discount_amount" class="form-control">
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Free Shipping') }}</label>
                        <select name="free_shipping" class="form-control">
                            <option value="0">{{ __('No') }}</option>
                            <option value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Tax Exempt') }}</label>
                        <select name="tax_exempt" class="form-control">
                            <option value="0">{{ __('No') }}</option>
                            <option value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Status') }}</label>
                        <select name="is_active" class="form-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="input__button">
                        <button type="submit" class="btn btn-blue">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection