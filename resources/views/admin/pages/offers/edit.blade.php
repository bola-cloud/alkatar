@extends('admin.master', ['menu' => 'offers'])
@section('title', isset($title) ? $title : '')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{ __('Edit Offer') }}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Offer') }}</li>
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
                                        action="{{ route('admin.offers.update', $edit->id) }}">
                                        @csrf

                                        <input type="hidden" name="id" value="{{$edit->id}}">
                                        <div class="input__group mb-25">
                                            <label for="offer_type">{{ __('Offer Type') }}</label>
                                            <select name="offer_type" id="offer_type" required>
                                                <option value="">select Offer Type</option>
                                                <option value="percentage_discount" {{ $edit->type == 'percentage_discount' ? 'selected' : '' }}>Percentage
                                                    Discount</option>
                                                <option value="fixed_discount" {{ $edit->type == 'fixed_discount' ? 'selected' : '' }}>Fixed Discount</option>
                                                <option value="buy_x_get_z" {{ $edit->type == 'buy_x_get_z' ? 'selected' : '' }}>Buy X Get Z</option>

                                                <option value="total_bill_discount" {{ $edit->type == 'total_bill_discount' ? 'selected' : '' }}>Total Bill
                                                    Discount</option>
                                                <option value="free_shipping_with_total_bill" {{ $edit->type == 'free_shipping_with_total_bill' ? 'selected' : '' }}>
                                                    Free Shipping With Total
                                                    Bill</option>

                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="applies_to_div"
                                            style="{{ $edit->type == 'percentage_discount' || $edit->type == 'fixed_discount' ? 'display: block;' : 'display: none;' }}">
                                            <label for="applies_to">{{ __('Applies To') }}</label>
                                            <select name="applies_to" id="applies_to" class="form-control">
                                                <option value="">-- select Applies To --</option>
                                                <option value="product" {{ $edit->applies_to == 'product' ? 'selected' : '' }}>on Product</option>
                                                <option value="category" {{ $edit->applies_to == 'category' ? 'selected' : '' }}>on Category</option>
                                                <option value="sub_category" {{ $edit->applies_to == 'sub_category' ? 'selected' : '' }}>on Sub Category</option>
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="product_div"
                                            style="{{ $edit->applies_to == 'product' ? 'display: block;' : 'display: none;' }}">
                                            <label for="product_id">{{ __('Product') }}</label>
                                            <select name="product_id" id="product_id" class="form-control">
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $product->id == $edit->product_id ? 'selected' : '' }}>{{ $product->fr_Product_Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="category_div"
                                            style="{{ $edit->applies_to == 'category' ? 'display: block;' : 'display: none;' }}">
                                            <label for="category_id">{{ __('Category') }}</label>
                                            <select name="category_id" id="category_id" class="form-control">
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}" {{ $category->id == $edit->category_id ? 'selected' : '' }}>{{ $category->fr_Category_Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="sub_category_id_div"
                                            style="{{ $edit->applies_to == 'sub_category' ? 'display: block;' : 'display: none;' }}">
                                            <label for="sub_category_id">{{ __('Sub Category') }}</label>
                                            <select name="sub_category_id" id="sub_category_id" class="form-control">
                                                @foreach ($sub_categorys as $sub_category)
                                                    <option value="{{ $sub_category->id }}" {{ $sub_category->id == $edit->sub_category_id ? 'selected' : '' }}>
                                                        {{ $sub_category->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ old('name', $edit->name) }}" placeholder="{{ __('Name') }}">
                                        </div>


                                        <div class="input__group mb-25">
                                            <label for="start_date">{{ __('Start Date') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                value="{{ old('start_date', $edit->start_date) }}"
                                                placeholder="{{ __('Start Date') }}">
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="end_date">{{ __('End Date') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                value="{{ old('end_date', $edit->end_date) }}"
                                                placeholder="{{ __('End Date') }}">
                                        </div>


                                        <div class="input__group mb-25" id="discount_value_div"
                                            style="{{ $edit->type == 'percentage_discount' || $edit->type == 'fixed_discount' || $edit->type == 'total_bill_discount' ? 'display: block;' : 'display: none;' }}">
                                            <label for="discount_value">{{ __('Discount Value') }}</label>
                                            <input type="number" name="discount_value" id="discount_value"
                                                class="form-control" step="0.01"
                                                value="{{ old('discount_value', $edit->discount_value) }}">
                                        </div>

                                        <div class="input__group mb-25" id="required_product_div"
                                            style="{{ $edit->type == 'buy_x_get_z' ? 'display: block;' : 'display: none;' }}">
                                            <label for="required_product_ids">{{ __('Required Product(s)') }}</label>
                                            <select name="required_product_ids[]" id="required_product_ids"
                                                class="form-control required_product_ids" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ in_array($product->id, $edit->required_product_ids ?? []) ? 'selected' : '' }}>
                                                        {{ $product->fr_Product_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="gift_product_div"
                                            style="{{ $edit->type == 'buy_x_get_z' ? 'display: block;' : 'display: none;' }}">
                                            <label for="gift_product_ids">{{ __('Gift Product(s)') }}</label>
                                            <select name="gift_product_ids[]" id="gift_product_ids"
                                                class="form-control gift_product_ids" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ in_array($product->id, $edit->gift_product_ids ?? []) ? 'selected' : '' }}>
                                                        {{ $product->fr_Product_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="minimum_total_div"
                                            style="{{ $edit->type == 'total_bill_discount' ? 'display: block;' : 'display: none;' }} {{ $edit->type == 'free_shipping_with_total_bill' ? 'display: block;' : 'display: none;' }}">
                                            <label for="minimum_total">{{ __('Minimum Total') }}</label>
                                            <input type="number" name="minimum_total" id="minimum_total"
                                                class="form-control" step="0.01"
                                                value="{{ old('minimum_total', $edit->minimum_total) }}">
                                        </div>

                                        <input type="hidden" name="is_percentage" id="is_percentage"
                                            value="{{ $edit->type == 'percentage_discount' ? '1' : '0' }}">

                                        <div class="input__button">
                                            <button type="submit" class="btn btn-blue">{{ __('Update') }}</button>
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

@push('post_scripts')
    <script>
        $(".required_product_ids").select2({ placeholder: "Select One", });
        $(".gift_product_ids").select2({ placeholder: "Select One", });

        document.getElementById('applies_to').addEventListener('change', function () {
            const selectedType = this.value;
            const productDiv = document.getElementById('product_div');
            const categoryDiv = document.getElementById('category_div');
            const subCategoryDiv = document.getElementById('sub_category_id_div');
            productDiv.style.display = 'none';

            if (selectedType === 'product') {
                productDiv.style.display = 'block';
                categoryDiv.style.display = 'none';
                subCategoryDiv.style.display = 'none';
            } else if (selectedType === 'category') {
                categoryDiv.style.display = 'block';
                productDiv.style.display = 'none';
                subCategoryDiv.style.display = 'none';
            } else if (selectedType === 'sub_category') {
                subCategoryDiv.style.display = 'block';
                productDiv.style.display = 'none';
                categoryDiv.style.display = 'none';
            }
        });

        document.getElementById('offer_type').addEventListener('change', function () {
            const selectedType = this.value;
            const isPercentageInput = document.getElementById('is_percentage');
            const discountValueDiv = document.getElementById('discount_value_div');
            const requiredProductDiv = document.getElementById('required_product_div');
            const giftProductDiv = document.getElementById('gift_product_div');
            const minimumTotalDiv = document.getElementById('minimum_total_div');
            const appliesToDiv = document.getElementById('applies_to_div');

            discountValueDiv.style.display = 'none';
            requiredProductDiv.style.display = 'none';
            giftProductDiv.style.display = 'none';
            minimumTotalDiv.style.display = 'none';
            appliesToDiv.style.display = 'none';

            if (selectedType === 'percentage_discount') {
                discountValueDiv.style.display = 'block';
                appliesToDiv.style.display = 'block';
                isPercentageInput.value = '1';
            } else if (selectedType === 'fixed_discount') {
                discountValueDiv.style.display = 'block';
                appliesToDiv.style.display = 'block';
                isPercentageInput.value = '0';
            } else if (selectedType === 'buy_x_get_z') {
                requiredProductDiv.style.display = 'block';
                giftProductDiv.style.display = 'block';
            } else if (selectedType === 'total_bill_discount') {
                minimumTotalDiv.style.display = 'block';
                discountValueDiv.style.display = 'block';
                isPercentageInput.value = '1';
            } else if (selectedType === 'free_shipping_with_total_bill') {
                minimum_total_div.style.display = 'block';
                isPercentageInput.value = '0';
            }
        });
    </script>
@endpush

@endsection