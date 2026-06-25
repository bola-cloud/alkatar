@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'custom_box_templates'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Edit Custom Box Template') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.custom_box_templates.index') }}">{{ __('Templates') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
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
                            <form method="POST" action="{{ route('admin.custom_box_templates.update', $template->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('English Details') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="name_en">{{ __('Template Name') }} (EN)</label>
                                                <input type="text" id="name_en" name="name_en" required value="{{ old('name_en', $template->name_en) }}" placeholder="{{ __('Name') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="description_en">{{ __('Description') }} (EN)</label>
                                                <textarea name="description_en" id="description_en" class="form-control" rows="4">{{ old('description_en', $template->description_en) }}</textarea>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="color_code">{{ __('Color Indicator (Hex or CSS Color)') }}</label>
                                                <input type="text" id="color_code" name="color_code" required value="{{ old('color_code', $template->color_code) }}" placeholder="#1A4231">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="price">{{ __('Base Packaging Price (OMR)') }}</label>
                                                <input type="number" step="0.001" min="0" id="price" name="price" required value="{{ old('price', $template->price) }}" placeholder="2.000">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="is_active">{{ __('Status') }}</label>
                                                <select class="form-control" name="is_active" id="is_active" required>
                                                    <option value="1" {{ $template->is_active == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                    <option value="0" {{ $template->is_active == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                                </select>
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('Arabic Details') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="name_ar">{{ __('Template Name') }} (AR)</label>
                                                <input type="text" id="name_ar" name="name_ar" required value="{{ old('name_ar', $template->name_ar) }}" placeholder="{{ __('Name') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="description_ar">{{ __('Description') }} (AR)</label>
                                                <textarea name="description_ar" id="description_ar" class="form-control" rows="4">{{ old('description_ar', $template->description_ar) }}</textarea>
                                            </div>
                                        </div>
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
