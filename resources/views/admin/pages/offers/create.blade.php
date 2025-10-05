@extends('admin.master', ['menu' => 'offers'])
@section('title', isset($title) ? $title : '')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{ __('Add Offer') }}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('offers') }}</li>
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
                                        action="{{ route('admin.offers.store') }}">
                                        @csrf
                                        <div class="input__group mb-25">
                                            <label for="offer_type">{{ __('Offer Type') }}</label>
                                            <select name="offer_type" id="offer_type" required>
                                                <option value="">اختار نوع العرض</option>
                                                <option value="percentage_discount">خصم بنسبة</option>
                                                <option value="fixed_discount">خصم مبلغ ثابت</option>
                                                <option value="buy_x_get_z">اشتري منتج واحصل على اخر مجاناً</option>
                                                <option value="total_bill_discount">خصم على اجمالي الفاتورة</option>
                                                <option value="free_shipping_with_total_bill">شحن مجاني</option>
                                            </select>
                                        </div>


                                        <div class="input__group mb-25" id="applies_to_div" style="display: none;">
                                            <label for="applies_to">{{ __('Applies To') }}</label>
                                            <select name="applies_to" id="applies_to" class="form-control">
                                                <option value="">-- اختر التنفيذ على --</option>
                                                <option value="product">على منتج</option>
                                                <option value="allProduct">على كل المنتجات</option>
                                                <option value="category">على قسم</option>
                                                <option value="sub_category">على قسم فرعي</option>
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="product_div" style="display: none;">
                                            <label for="product_id">المنتج</label>
                                            <select name="product_id" id="product_id" class="form-control">
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->fr_Product_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="category_div" style="display: none;">
                                            <label for="category_id">القسم</label>
                                            <select name="category_id" id="category_id" class="form-control">
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}">{{ $category->fr_Category_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="input__group mb-25" id="sub_category_id_div" style="display: none;">
                                            <label for="sub_category_id">القسم الفرعي</label>
                                            <select name="sub_category_id" id="sub_category_id" class="form-control">
                                                @foreach ($sub_categorys as $sub_category)
                                                    <option value="{{ $sub_category->id }}">{{ $sub_category->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="input__group mb-25">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="{{ __('Name') }}">
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="start_date">{{ __('Start Date') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                placeholder="{{ __('Start Date') }}">
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="end_date">{{ __('End Date') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                placeholder="{{ __('End Date') }}">
                                        </div>


                                        <div class="input__group mb-25" id="discount_value_div" style="display: none;">
                                            <label for="discount_value">{{ __('Discount Value') }}</label>
                                            <input type="number" name="discount_value" id="discount_value"
                                                class="form-control" step="0.01">
                                        </div>

                                        <div class="input__group mb-25" id="required_product_div"
                                            style="display: none;">
                                            <label for="required_product_ids">المنتجات المطلوب شراءها</label>
                                            <select name="required_product_ids[]" id="required_product_ids"
                                                class="form-control required_product_ids" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->fr_Product_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="input__group mb-25" id="gift_product_div" style="display: none;">
                                            <label for="gift_product_ids">المنتجات المجانية</label>
                                            <select name="gift_product_ids[]" id="gift_product_ids"
                                                class="form-control gift_product_ids" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->fr_Product_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input__group mb-25" id="minimum_total_div" style="display: none;">
                                            <label for="minimum_total">الحد الأدنى</label>
                                            <input type="number" name="minimum_total" id="minimum_total"
                                                class="form-control" step="0.01">
                                        </div>
                                        <input type="hidden" name="is_percentage" id="is_percentage" value="0">

                                        <div class="input__button">
                                            <button type="submit" class="btn btn-blue">{{ __('Add') }}</button>
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

    </script>

    <script>
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
                minimum_total_div.style.display = 'block';
                discountValueDiv.style.display = 'block';
                isPercentageInput.value = '1';
            }else if(selectedType === 'free_shipping_with_total_bill'){
                minimum_total_div.style.display = 'block';
                isPercentageInput.value = '0';
            }
        });
    </script>

@endpush

@endsection