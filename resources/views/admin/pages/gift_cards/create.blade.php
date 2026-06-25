@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'gift_card_packages'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Create Gift Card Package') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-vertical__item bg-style">
                <form method="post" action="{{ route('admin.gift_card_packages.store') }}">
                    @csrf
                    <div class="input__group mb-25">
                        <label>{{ __('Package Key (lowercase, e.g. gold, silver, bronze)') }}</label>
                        <input type="text" name="key" class="form-control" placeholder="gold" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" class="form-control" placeholder="الباقة الذهبية" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Name (English)') }}</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Gold Package" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Price') }}</label>
                        <input type="number" step="0.001" name="price" class="form-control" value="0.000" min="0" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description (Arabic)') }}</label>
                        <textarea name="description_ar" class="form-control" rows="3" placeholder="رصيد إهداء بقيمة ٥٠٠ ر.ع"></textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description (English)') }}</label>
                        <textarea name="description_en" class="form-control" rows="3" placeholder="Gift credit with 500 OMR value"></textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-control">
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
