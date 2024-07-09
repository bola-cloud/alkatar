@extends('admin.master', ['menu' => 'catbad', 'submenu' => 'add_addition'])
@section('title', isset($title) ? $title : '')
@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{__('Add Addition')}}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Category')}}</li>
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
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-vertical__item bg-style">
                                    <form enctype="multipart/form-data" method="POST"
                                        action="{{route('admin.physical.product.addition.store')}}">
                                        @csrf
                                        <div class="input__group mb-25">
                                            <label>{{ __('Addition Name '.langString('en'))}}</label>
                                            <input type="text" id="en_addition_name" name="en_addition_name"
                                                value="{{ old('en_addition_name') }}" placeholder="Name (English)" required>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label>{{ __('Addition Name '.langString('fr'))}}</label>
                                            <input type="text" id="fr_addition_name" name="fr_addition_name"
                                                value="{{ old('fr_addition_name') }}" placeholder="Name (Arabic)" required>
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="product_id">{{ __('Select Product') }}</label>
                                            <select name="product_id" id="product_id" class="form-control select2">
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->en_Product_Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25">
                                            <label>{{ __('Price')}}</label>
                                            <input type="number" id="price" required name="price" value="{{ old('price') }}"
                                                placeholder="Price" step="0.001">
                                        </div>
                                        
                                        {{-- <div class="input__group mb-25">
                                            <label>{{ __('Icon')}} (250x250)</label>
                                            <input type="file" id="icon" name="icon" accept="image/*">
                                        </div> --}}

                                        <div class="input__group">
                                            <label>{{ __('Active')}}</label>
                                            <label class="switch" for="status">
                                                <input type="checkbox" id="status" name="status" value="1" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="input__button">
                                            <button type="submit" class="btn btn-blue">{{ __('Add')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a product",
            allowClear: true
        });
    });
</script>
@endsection

