@extends('admin.master', ['menu' => 'catbad', 'submenu' => 'subcategory'])
@section('title', isset($title) ? $title : '')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{__('Add Subcategory')}}</h2>
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
                                        action="{{route('admin.subcategory.store')}}">
                                        @csrf
                                        <div class="input__group mb-25">
                                            <label>{{ __('Subcategory Name ' . langString('en'))}}</label>
                                            <input type="text" id="name" name="name"
                                                value="{{ old('name') }}" placeholder="Name (English)">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label>{{ __('Subcategory Name ' . langString('fr'))}}</label>
                                            <input type="text" id="name_ar" name="name_ar"
                                                value="{{ old('name_ar') }}" placeholder="Name (Arabic)">
                                        </div>

                                        <div class="input__group mb-25">
                                            <label for="categorySelect">
                                                Category
                                            </label>
                                            <select id="categorySelect" name="category_id">
                                                @foreach(Category() as $category)
                                                    <option value="{{$category->id}}">
                                                        {{$category->en_Category_Name}}
                                                    </option>
                                                @endforeach
                                            </select>
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
@endsection