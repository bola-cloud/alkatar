@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'gift_card_packages'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Edit Gift Card Package') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-vertical__item bg-style">
                <form method="post" action="{{ route('admin.gift_card_packages.update', $package->id) }}">
                    @csrf
                    <div class="input__group mb-25">
                        <label>{{ __('Package Key (lowercase, e.g. gold, silver, bronze)') }}</label>
                        <input type="text" name="key" class="form-control" value="{{ $package->key }}" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ $package->name_ar }}" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Name (English)') }}</label>
                        <input type="text" name="name_en" class="form-control" value="{{ $package->name_en }}" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Price') }}</label>
                        <input type="number" step="0.001" name="price" class="form-control" value="{{ $package->price }}" min="0" required>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description (Arabic)') }}</label>
                        <textarea name="description_ar" class="form-control" rows="3">{{ $package->description_ar }}</textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Description (English)') }}</label>
                        <textarea name="description_en" class="form-control" rows="3">{{ $package->description_en }}</textarea>
                    </div>
                    <div class="input__group mb-25">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
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
