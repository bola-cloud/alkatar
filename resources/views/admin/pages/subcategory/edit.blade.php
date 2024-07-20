@extends('admin.master', ['menu' => 'catbad', 'submenu' => 'category'])
@section('title', isset($title) ? $title : '')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{__('Edit SubCategory')}}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Subcategory')}}</li>
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
                                        action="{{route('admin.subcategory.update')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$edit->id}}">
                                        <div class="input__group mb-25">
                                            <label>{{ __('Subcategory Name ' . langString('en'))}}</label>
                                            <input type="text" id="name" name="name"
                                                value="{{$edit->name}}" placeholder="Name (English)">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label>{{ __('Subcategory Name ' . langString('fr'))}}</label>
                                            <input type="text" id="name_ar" name="name_ar"
                                                value="{{$edit->name_ar}}" placeholder="Name (Arabic)">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="categorySelect">
                                                Category
                                            </label>
                                            <select id="categorySelect" name="category_id" >
                                                @foreach(Category() as $category)
                                                    <option value="{{$category->id}}" {{$edit->category_id == $category->id ? 'selected' : ''}}>
                                                        {{$category?->en_Category_Name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- <div class="input__group mb-25">
                                            <label>{{ __('Icon')}} (200x200)</label>
                                            <input type="file" id="icon" name="icon" accept="image/*">
                                            @if($edit->Category_Icon)
                                                <img src="{{ asset(CategoryImage() . $edit->Category_Icon) }}"
                                                    alt="Current Image" width="100">
                                            @endif
                                        </div> -->
                                        {{-- <div class="input__group mb-25">
                                            <label>{{__('Description '.langString('en'))}}</label>
                                            <textarea name="en_description" id="en_description"
                                                placeholder="Description (English)">{{$edit->en_Description}}</textarea>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label>{{__('Description '.langString('fr'))}}</label>
                                            <textarea name="fr_description" id="fr_description"
                                                placeholder="Description (Arabic)">{{$edit->fr_Description}}</textarea>
                                        </div> --}}
                                        <div class="input__button">
                                            <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
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
@endsection

<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script>
    // preview image before upload
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#icon').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#icon").change(function () {
        readURL(this);
    });

</script>