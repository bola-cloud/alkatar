@extends('admin.master', ['menu' => 'products', 'submenu' => 'product'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Edit Product') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
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
                            <form enctype="multipart/form-data" method="POST" action="{{ route('admin.product.update') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-xxl-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ langString('en', false) . ':' }}</h2>
                                            </div>
                                            <input type="hidden" name="product_type" value="{{ PRODUCT_PHYSICAL }}">
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Product Name') }}</label>
                                                <input type="text" class="form-control" id="en_product_name"
                                                    name="en_product_name" value="{{ $product->en_Product_Name }}"
                                                    placeholder="{{ __('Name') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="en-product-slug">{{ __('Product Slug') }}</label>
                                                <input type="text" class="form-control" id="en-product-slug"
                                                    name="en_product_slug" value="{{ $product->en_Product_Slug }}"
                                                    placeholder="Slug">
                                                @error('en_product_slug')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            {{-- <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Brand Name') }}</label>
                                                <select class="form-control" id="en_brand_name" name="en_brand_name">
                                                    <option value="">{{ __('---SELECT A BRAND---') }}</option>
                                                    @foreach (Brnad() as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $product->Brand_Id ? 'selected' : '' }}>
                                                            {{ $item->en_BrandName }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Category Name') }}</label>
                                                <select class="form-control" id="en_category_name" name="en_category_name">
                                                    <option value="">{{ __('---SELECT A CATEGORY---') }}</option>
                                                    @foreach (Category_Des_Icon() as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $product->Category_Id ? 'selected' : '' }}>
                                                            {{ $item->en_Category_Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="input__group mb-25">
                                            <label for="subcategory_id">{{__("Subcategory Name")}}</label>
                                            <select class="form-control" id="subcategory_id" name="subcategory_id" data-subcategory-id="{{$product->subcategory_id}}">
                                                <option value="">{{ __('Select Subcategory') }}</option>
                                            </select>
                                            @error('subcategory_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                            {{-- <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Item Tag') }}</label>
                                                <select class="form-control" id="item_teg" name="item_teg">
                                                    <option value="">{{ __('---Select item---') }}</option>
                                                    @foreach ($item_tags as $it)
                                                        <option value="{{ $it->name }}"
                                                            {{ $it->name == $product->ItemTag ? 'selected' : '' }}>
                                                            {{ $it->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="input__group mb-25">
                                                <label for="select2Multiple">{{ __('Product Tag') }}</label>
                                                <select class="select2-multiple form-control tag_two" name="product_tag[]"
                                                    multiple="multiple">
                                                    <option value="">{{ __('---SELECT A PRODUCT TAG---') }}</option>
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->name }}_{{$tag->name_ar}}"
                                                            {{ selectProductTag($tag->name, $product->id) }}>
                                                            {{ $tag->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            {{-- <div class="input__group mb-25">
                                                <label for="select2Multiple">{{ __('Product Color') }}</label>
                                                <select class="select2-multiple form-control tag_two" name="color[]"
                                                    multiple="multiple">
                                                    @foreach ($colors as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ colorSelected($product->id, $item->id) == 1 ? 'selected' : '' }}>
                                                            {{ $item->Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> 

                                            <div class="input__group mb-25">
                                                <label for="select2Multiple">{{ __('Product Size') }}</label>
                                                <select class="select2-multiple form-control tag_one" name="size[]"
                                                    multiple="multiple">
                                                    @foreach ($sizes as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ sizeSelected($product->id, $item->id) == 1 ? 'selected' : '' }}>
                                                            {{ $item->Size }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}

                                            <div class="input__group mb-25">
                                                <label>{{ __('Product Option') }}</label>
                                                <div id="size-container">
                                                    <!-- Size rows will be added here dynamically -->
                                                </div>
                                                <button type="button" class="btn btn-primary" id="add-size-btn">Add Size</button>
                                            </div>

                                            <div class="input__group mb-25">
                                            <label>{{ __('Product Weight') }}</label>
                                            <div id="weight-container">
                                                <!-- Size rows will be added here dynamically -->
                                            </div>
                                            <button type="button" class="btn btn-primary" id="add-weight-btn">Add
                                            Weight</button>
                                        </div>
                                            
                                            

                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Points To Add') }}</label>
                                                <input type="text" class="form-control" id="points"
                                                    name="points" value="{{ $product->points }}"
                                                    placeholder="{{ __('Points to add') }}">
                                            </div>

                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Quantity') }}</label>
                                                <input type="text" class="form-control" id="qty" name="qty"
                                                    value="{{ $product->Quantity }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Price') }}</label>
                                                <input type="text" class="form-control" id="price" name="price"
                                                    value="{{ $product->Price }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label
                                                    for="exampleInputEmail1">{{ __('Discount (in Percentage)') }}</label>
                                                <input type="number" min="0" class="form-control" id="discount"
                                                    name="discount" value="{{ $product->Discount }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Discount Price') }}</label>
                                                <input type="number" class="form-control"
                                                    value="{{ $product->Discount_Price }}" id="discount_price"
                                                    name="discount_price" readonly>
                                            </div>


                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Description') }}</label>
                                                <textarea name="en_description" id="summernote" class="form-control">{!! $product->en_Description !!}</textarea>
                                            </div>

                                            {{-- <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('ShippingReturn') }}</label>
                                                <textarea name="en_shippingreturn" id="summernote2" class="form-control">{!! $product->en_ShippingReturn !!}</textarea>
                                            </div> --}}
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('AdditionalInformation') }}</label>
                                                <textarea name="en_additionalinformation" id="summernote3" class="form-control">{!! $product->en_AdditionalInformation !!}</textarea>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Primary Image') }} (320x250)</label>
                                                <input type="file" class="form-control putImage1" name="primary_image"
                                                    id="primary_image">
                                                <img class="admin_image"
                                                    src="{{ asset(ProductImage() . $product->Primary_Image) }}"
                                                    id="target1" />
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Image 2') }}</label>
                                                <input type="file" class="form-control putImage2" name="image_two"
                                                    id="image_two">
                                                <img class="admin_image"
                                                    src="{{ asset(ProductImage() . $product->Image2) }}"
                                                    id="target2" />
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Image Gallery Image') }}</label>
                                                <input type="file" class="form-control putImage3" name="image_three"
                                                    id="image_three">
                                                <img class="admin_image"
                                                    src="{{ asset(ProductImage() . $product->Image3) }}"
                                                    id="target3" />
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Image 3') }}</label>
                                                <input type="file" class="form-control putImage4" name="image_four"
                                                    id="image_four">
                                                <img class="admin_image"
                                                    src="{{ asset(ProductImage() . $product->Image4) }}"
                                                    id="target4" />
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Image 4') }}</label>
                                                <input type="file" class="form-control putImage5" name="image_five"
                                                    id="image_five">
                                                <img class="admin_image"
                                                    src="{{ asset(ProductImage() . $product->Image5) }}"
                                                    id="target5" />
                                            </div>

                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" value="1"
                                                        {{ $product->Status == 1 ? 'checked' : '' }} name="status"
                                                        class="custom-control-input" id="customSwitch1">
                                                    <label class="custom-control-label"
                                                        for="customSwitch1">{{ __('Active') }}</label>
                                                </div>
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" value="1"
                                                        {{ $product->Featured_Product == 1 ? 'checked' : '' }}
                                                        name="feature" class="custom-control-input" id="customSwitch2">
                                                    <label class="custom-control-label"
                                                        for="customSwitch2">{{ __('Popular') }}</label>
                                                </div>
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" value="1"
                                                        {{ $product->Best_Selling == 1 ? 'checked' : '' }}
                                                        name="best_sale" class="custom-control-input" id="customSwitch3">
                                                    <label class="custom-control-label"
                                                        for="customSwitch3">{{ __('Best Selling') }}</label>
                                                </div>
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" value="1"
                                                        {{ $product->On_Sale == 1 ? 'checked' : '' }} name="on_sale"
                                                        class="custom-control-input" id="customSwitch4">
                                                    <label class="custom-control-label"
                                                        for="customSwitch4">{{ __('On Sale') }}</label>
                                                </div>
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" value="1"
                                                        {{ $product->New_Arrival == 1 ? 'checked' : '' }}
                                                        name="on_arrival" class="custom-control-input"
                                                        id="customSwitch5">
                                                    <label class="custom-control-label"
                                                        for="customSwitch5">{{ __('New Arrival') }}</label>
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ langString('fr', false) . ':' }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Product Name') }}</label>
                                                <input type="text" class="form-control" id="fr_product_name"
                                                    value="{{ $product->fr_Product_Name }}" name="fr_product_name">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="fr-product-slug">{{ __('Product Slug') }}</label>
                                                <input type="text" class="form-control" id="fr-product-slug"
                                                    name="fr_product_slug" value="{{ $product->fr_Product_Slug }}"
                                                    placeholder="{{ __('Slug') }}">
                                                @error('fr_product_slug')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                       
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Description') }}</label>
                                                <textarea name="fr_description" id="summernote4" class="form-control">{!! $product->fr_Description !!}</textarea>
                                            </div>
                                            {{-- <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('ShippingReturn') }}</label>
                                                <textarea name="fr_shippingreturn" id="summernote5" class="form-control">{!! $product->fr_ShippingReturn !!}</textarea>
                                            </div> --}}
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('AdditionalInformation') }}</label>
                                                <textarea name="fr_additionalinformation" id="summernote6" class="form-control">{!! $product->fr_AdditionalInformation !!}</textarea>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update') }}</button>
                                            </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('post_scripts')
    <script src="{{ asset('backend/js/admin/products/physical-edit.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            $("#summernote").summernote({
                placeholder: 'Description',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });

        $(document).ready(function() {
            $("#summernote2").summernote({
                placeholder: 'Shipping Return',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });
        $(document).ready(function() {
            $("#summernote3").summernote({
                placeholder: 'Additional Information',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });
        $(document).ready(function() {
            $("#summernote4").summernote({
                placeholder: 'Description',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });

        $(document).ready(function() {
            $("#summernote5").summernote({
                placeholder: 'Shipping Return',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });
        $(document).ready(function() {
            $("#summernote6").summernote({
                placeholder: 'Additional Information',
                height: 300
            });
            $('.dropdown-toggle').dropdown();
        });
    </script>

<script>
    $(document).ready(function() {
        let sizeCounter = 0;

        // Function to create a new size row
        function createSizeRow(sizeId = null, price = null, weight = null) {
            sizeCounter++;
            const sizeRow = `
         <div class="row mb-3" id="size-row-${sizeCounter}">
            <div class="col-md-5">
                <label for="size-${sizeCounter}">Size:</label>
                <select class="form-control" name="size[]" id="size-${sizeCounter}">
                    @foreach ($sizes as $item)
                        <option value="{{ $item->id }}" ${sizeId === {{ $item->id }} ? 'selected' : ''}>{{ $item->Size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="price-${sizeCounter}">Price:</label>
                <input type="text" class="form-control" id="price-${sizeCounter}" name="size_price[]" placeholder="Enter Price" value="${price || ''}">
            </div>
            <div class="col-md-3">
                <label for="weight-${sizeCounter}">Weight (grams):</label>
                <input type="text" class="form-control" required id="weight-${sizeCounter}" name="size_weight[]" placeholder="Enter Weight (grams)" value="${weight || ''}">
            </div>
            <div class="col-md-1">
                <label class="d-block">&nbsp;</label>
                <button type="button" class="btn btn-danger remove-size-row" data-row-id="size-row-${sizeCounter}">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
            `;

            $('#size-container').append(sizeRow);
        }


             // Pre-populate existing sizes
             @foreach ($product->sizes as $size)
            createSizeRow({{ $size->pivot->Size_Id }}, '{{ $size->pivot->price }}', '{{ $size->pivot->weight }}');
        @endforeach

         // Add initial size row if no existing sizes
        //  if (!{{ $product->sizes->count() }}) {
        //     createSizeRow();
        // }

        // Add event listener for the "Add Size" button
        $('#add-size-btn').click(function() {
            createSizeRow();
        });

        // Add event listener for removing size rows
        $(document).on('click', '.remove-size-row', function() {
            const rowId = $(this).data('row-id');
            $(`#${rowId}`).remove();
        });
    });
</script>

<script>
   $(document).ready(function () {
    let weightCounter = 0;

    // Function to create a new weight row
    function createWeightRow(weightId = null, price = null, weight = null) {
        weightCounter++;
        const weightRow = `
            <div class="row mb-3" id="weight-row-${weightCounter}">
                <div class="col-md-3">
                    <label for="weight-${weightCounter}">Weight (grams):</label>
                    <input type="text" id="weight-${weightCounter}" class="form-control" required name="weight_amount[]" placeholder="Enter Weight (grams)" value="${weight || ''}">
                </div>
                <div class="col-md-3">
                    <label for="price-${weightCounter}">Price:</label>
                    <input type="text" class="form-control" required name="weight_price[]" id="price-${weightCounter}" placeholder="Enter Price" value="${price || ''}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-weight-row" data-row-id="weight-row-${weightCounter}">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        $('#weight-container').append(weightRow);
    }


         // Pre-populate existing sizes
         @foreach ($product->weights as $weight)
         createWeightRow({{ $weight->id }}, '{{ $weight->price }}', '{{ $weight->weight }}');
        @endforeach

         // Add initial size row if no existing sizes
         if (!{{ $product->weights->count() }}) {
            createWeightRow();
        }

    // Add event listener for the "Add Weight" button
    $('#add-weight-btn').click(function () {
        createWeightRow();
    });

    // Add event listener for removing weight rows
    $(document).on('click', '.remove-weight-row', function () {
        const rowId = $(this).data('row-id');
        $(`#${rowId}`).remove();
    });
});
    </script>


  <script>
        $(document).ready(function () {
            $('#en_category_name').change(function () {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: '{{ route("admin.subcategory.all") }}',
                        type: 'GET',
                        data: { category_id: categoryId },
                        success: function (data) {
                            $('#subcategory_id').empty();
                            $('#subcategory_id').append('<option value="">{{ __("Select Subcategory") }}</option>');
                            $.each(data, function (key, value) {
                                $('#subcategory_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subcategory_id').empty();
                    $('#subcategory_id').append('<option value="">{{ __("Select Subcategory") }}</option>');
                }
            });
        });
    </script>
@endpush
