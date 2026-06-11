@extends('admin.master', ['menu' => 'products', 'submenu' => 'offers_packages'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Create Offers Package') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.offers-packages.index') }}">{{ __('Offers Packages') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Offers Package') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style">
                <div class="gallery__content">
                    <form enctype="multipart/form-data" method="POST" action="{{ route('admin.offers-packages.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Left Column: English Details -->
                            <div class="col-md-6">
                                <div class="form-vertical__item bg-style">
                                    <div class="item-top mb-30">
                                        <h2>English details:</h2>
                                    </div>
                                    
                                    <div class="input__group mb-25">
                                        <label for="en_name">{{ __('Package Name (English)') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="en_name" name="en_name" value="{{ old('en_name') }}" placeholder="{{ __('Package Name (English)') }}" required>
                                        @error('en_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input__group mb-25">
                                        <label for="en_about">{{ __('Package Contents (English)') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="en_about" name="en_about" rows="6" placeholder="Describe the items included in this package..." required>{{ old('en_about') }}</textarea>
                                        @error('en_about')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input__group mb-25">
                                                <label for="price">{{ __('Price Before Offer') }}<span class="text-danger">*</span></label>
                                                <input type="number" step="0.001" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder="0.000" required>
                                                @error('price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input__group mb-25">
                                                <label for="discount_price">{{ __('Price After Offer') }}<span class="text-danger">*</span></label>
                                                <input type="number" step="0.001" class="form-control" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" placeholder="0.000" required>
                                                @error('discount_price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input__group mb-25">
                                                <label for="qty">{{ __('Available Quantity') }}<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="qty" name="qty" value="{{ old('qty', 100) }}" placeholder="100" required>
                                                @error('qty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input__group mb-25">
                                                <label for="primary_image">{{ __('Primary Image') }}<span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="primary_image" name="primary_image" accept="image/*" required>
                                                @error('primary_image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input__group mb-25">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" value="1" name="status" class="custom-control-input" id="statusSwitch" checked>
                                            <label class="custom-control-label" for="statusSwitch">{{ __('Status') }} ({{ __('Active') }})</label>
                                        </div>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input__button mt-30">
                                        <button type="submit" class="btn btn-blue">{{ __('Submit') }}</button>
                                        <a href="{{ route('admin.offers-packages.index') }}" class="btn btn-secondary ml-2">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Arabic Details (fr) -->
                            <div class="col-md-6">
                                <div class="form-vertical__item bg-style">
                                    <div class="item-top mb-30">
                                        <h2>Arabic details:</h2>
                                    </div>

                                    <div class="input__group mb-25">
                                        <label for="fr_name">{{ __('Package Name (Arabic)') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fr_name" name="fr_name" value="{{ old('fr_name') }}" placeholder="{{ __('Package Name (Arabic)') }}" required>
                                        @error('fr_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input__group mb-25">
                                        <label for="fr_about">{{ __('Package Contents (Arabic)') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="fr_about" name="fr_about" rows="6" placeholder="اكتب محتويات وتفاصيل هذا العرض..." required>{{ old('fr_about') }}</textarea>
                                        @error('fr_about')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
