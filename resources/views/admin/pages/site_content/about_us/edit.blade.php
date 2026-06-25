@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'content_about'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('About Us Page Settings') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('About Us Settings') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style p-4">
                <form enctype="multipart/form-data" method="POST" action="{{ route('admin.about.page.site.content.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $edit->id }}">

                    <!-- 1. Hero Section -->
                    <div class="card mb-4" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                        <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                            <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-heading mr-2"></i> {{ __('1. Hero Section (قسم البانر الرئيسي)') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- English -->
                                <div class="col-md-6 border-right">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('English Content') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="en_Title">{{ __('Hero Title') }}</label>
                                        <input type="text" class="form-control" id="en_Title" name="en_Title" value="{{ $edit->en_Title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_subtitle">{{ __('Hero Subtitle') }}</label>
                                        <textarea class="form-control" id="en_subtitle" name="en_subtitle" rows="3">{{ $edit->en_Subtitle }}</textarea>
                                    </div>
                                </div>
                                <!-- Arabic -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Arabic Content') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="fr_Title">{{ __('Hero Title') }}</label>
                                        <input type="text" class="form-control" id="fr_Title" name="fr_Title" value="{{ $edit->fr_Title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_subtitle">{{ __('Hero Subtitle') }}</label>
                                        <textarea class="form-control" id="fr_subtitle" name="fr_subtitle" rows="3">{{ $edit->fr_Subtitle }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Vision & Mission Section -->
                    <div class="card mb-4" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                        <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                            <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-eye mr-2"></i> {{ __('2. Vision & Mission Section (الرؤية والرسالة)') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- English -->
                                <div class="col-md-6 border-right">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('English Content') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="en_vision_label">{{ __('Vision Label') }}</label>
                                        <input type="text" class="form-control" id="en_vision_label" name="en_vision_label" value="{{ $edit->en_vision_label }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_title_one">{{ __('Vision Title') }}</label>
                                        <input type="text" class="form-control" id="en_title_one" name="en_title_one" value="{{ $edit->en_Title_One }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_description_one">{{ __('Vision Description') }}</label>
                                        <textarea class="form-control" id="en_description_one" name="en_description_one" rows="4">{{ $edit->en_Description_One }}</textarea>
                                    </div>
                                    <hr class="my-4">
                                    <div class="input__group mb-3">
                                        <label for="en_mission_label">{{ __('Mission Label') }}</label>
                                        <input type="text" class="form-control" id="en_mission_label" name="en_mission_label" value="{{ $edit->en_mission_label }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_title_two">{{ __('Mission Title') }}</label>
                                        <input type="text" class="form-control" id="en_title_two" name="en_title_two" value="{{ $edit->en_Title_Two }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_description_two">{{ __('Mission Description') }}</label>
                                        <textarea class="form-control" id="en_description_two" name="en_description_two" rows="4">{{ $edit->en_Description_Two }}</textarea>
                                    </div>
                                </div>
                                <!-- Arabic -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Arabic Content') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="fr_vision_label">{{ __('Vision Label') }}</label>
                                        <input type="text" class="form-control" id="fr_vision_label" name="fr_vision_label" value="{{ $edit->fr_vision_label }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_title_one">{{ __('Vision Title') }}</label>
                                        <input type="text" class="form-control" id="fr_title_one" name="fr_title_one" value="{{ $edit->fr_Title_One }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_description_one">{{ __('Vision Description') }}</label>
                                        <textarea class="form-control" id="fr_description_one" name="fr_description_one" rows="4">{{ $edit->fr_Description_One }}</textarea>
                                    </div>
                                    <hr class="my-4">
                                    <div class="input__group mb-3">
                                        <label for="fr_mission_label">{{ __('Mission Label') }}</label>
                                        <input type="text" class="form-control" id="fr_mission_label" name="fr_mission_label" value="{{ $edit->fr_mission_label }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_title_two">{{ __('Mission Title') }}</label>
                                        <input type="text" class="form-control" id="fr_title_two" name="fr_title_two" value="{{ $edit->fr_Title_Two }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_description_two">{{ __('Mission Description') }}</label>
                                        <textarea class="form-control" id="fr_description_two" name="fr_description_two" rows="4">{{ $edit->fr_Description_Two }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Images & Badges') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="image">{{ __('Roaster Image') }} (450x450)</label>
                                        <input type="file" class="form-control putImage1" name="image" id="image">
                                        @if($edit->Image)
                                            <img class="admin_image mt-2" src="{{ asset(aboutUsPage() . $edit->Image) }}" id="target1" style="max-height: 150px; border-radius: 8px;" />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Experience Badge') }}</h6>
                                    <div class="input__group mb-3">
                                        <label for="experience_years">{{ __('Experience Years (e.g. 10+)') }}</label>
                                        <input type="text" class="form-control" id="experience_years" name="experience_years" value="{{ $edit->experience_years }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input__group mb-3">
                                                <label for="en_experience_text">{{ __('Badge Text (English)') }}</label>
                                                <input type="text" class="form-control" id="en_experience_text" name="en_experience_text" value="{{ $edit->en_experience_text }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input__group mb-3">
                                                <label for="fr_experience_text">{{ __('Badge Text (Arabic)') }}</label>
                                                <input type="text" class="form-control" id="fr_experience_text" name="fr_experience_text" value="{{ $edit->fr_experience_text }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Core Values Section -->
                    <div class="card mb-4" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                        <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                            <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-gem mr-2"></i> {{ __('3. Core Values Section (القيم الجوهرية)') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Section Heading -->
                            <div class="row mb-4">
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-3">
                                        <label for="en_values_title">{{ __('Values Section Title (English)') }}</label>
                                        <input type="text" class="form-control" id="en_values_title" name="en_values_title" value="{{ $edit->en_values_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_values_subtitle">{{ __('Values Section Subtitle (English)') }}</label>
                                        <textarea class="form-control" id="en_values_subtitle" name="en_values_subtitle" rows="2">{{ $edit->en_values_subtitle }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-3">
                                        <label for="fr_values_title">{{ __('Values Section Title (Arabic)') }}</label>
                                        <input type="text" class="form-control" id="fr_values_title" name="fr_values_title" value="{{ $edit->fr_values_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_values_subtitle">{{ __('Values Section Subtitle (Arabic)') }}</label>
                                        <textarea class="form-control" id="fr_values_subtitle" name="fr_values_subtitle" rows="2">{{ $edit->fr_values_subtitle }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">

                            <!-- Value 1: Community -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Value 1 (المجتمع)') }}</h6>
                                </div>
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Title') }}</label>
                                        <input type="text" class="form-control" name="en_value_one_title" value="{{ $edit->en_value_one_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Description') }}</label>
                                        <textarea class="form-control" name="en_value_one_description" rows="2">{{ $edit->en_value_one_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Title') }}</label>
                                        <input type="text" class="form-control" name="fr_value_one_title" value="{{ $edit->fr_value_one_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Description') }}</label>
                                        <textarea class="form-control" name="fr_value_one_description" rows="2">{{ $edit->fr_value_one_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">

                            <!-- Value 2: Education -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Value 2 (التعليم)') }}</h6>
                                </div>
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Title') }}</label>
                                        <input type="text" class="form-control" name="en_value_two_title" value="{{ $edit->en_value_two_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Description') }}</label>
                                        <textarea class="form-control" name="en_value_two_description" rows="2">{{ $edit->en_value_two_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Title') }}</label>
                                        <input type="text" class="form-control" name="fr_value_two_title" value="{{ $edit->fr_value_two_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Description') }}</label>
                                        <textarea class="form-control" name="fr_value_two_description" rows="2">{{ $edit->fr_value_two_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">

                            <!-- Value 3: Sustainability -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Value 3 (الاستدامة)') }}</h6>
                                </div>
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Title') }}</label>
                                        <input type="text" class="form-control" name="en_value_three_title" value="{{ $edit->en_value_three_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Description') }}</label>
                                        <textarea class="form-control" name="en_value_three_description" rows="2">{{ $edit->en_value_three_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Title') }}</label>
                                        <input type="text" class="form-control" name="fr_value_three_title" value="{{ $edit->fr_value_three_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Description') }}</label>
                                        <textarea class="form-control" name="fr_value_three_description" rows="2">{{ $edit->fr_value_three_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">

                            <!-- Value 4: Quality -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold text-secondary mb-3">{{ __('Value 4 (الجودة)') }}</h6>
                                </div>
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Title') }}</label>
                                        <input type="text" class="form-control" name="en_value_four_title" value="{{ $edit->en_value_four_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('English Description') }}</label>
                                        <textarea class="form-control" name="en_value_four_description" rows="2">{{ $edit->en_value_four_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Title') }}</label>
                                        <input type="text" class="form-control" name="fr_value_four_title" value="{{ $edit->fr_value_four_title }}">
                                    </div>
                                    <div class="input__group mb-2">
                                        <label>{{ __('Arabic Description') }}</label>
                                        <textarea class="form-control" name="fr_value_four_description" rows="2">{{ $edit->fr_value_four_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Why Al-Katar Section -->
                    <div class="card mb-4" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                        <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                            <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-circle-question mr-2"></i> {{ __('4. Why Al-Katar Section (ما الذي يميز القطار؟)') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-3">
                                        <label for="en_why_title">{{ __('Why Title (English)') }}</label>
                                        <input type="text" class="form-control" id="en_why_title" name="en_why_title" value="{{ $edit->en_why_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_why_subtitle">{{ __('Why Subtitle (English)') }}</label>
                                        <textarea class="form-control" id="en_why_subtitle" name="en_why_subtitle" rows="3">{{ $edit->en_why_subtitle }}</textarea>
                                    </div>
                                    <hr class="my-3">
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 1 (English)') }}</label>
                                        <input type="text" class="form-control" name="en_why_item_one" value="{{ $edit->en_why_item_one }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 2 (English)') }}</label>
                                        <input type="text" class="form-control" name="en_why_item_two" value="{{ $edit->en_why_item_two }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 3 (English)') }}</label>
                                        <input type="text" class="form-control" name="en_why_item_three" value="{{ $edit->en_why_item_three }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-3">
                                        <label for="fr_why_title">{{ __('Why Title (Arabic)') }}</label>
                                        <input type="text" class="form-control" id="fr_why_title" name="fr_why_title" value="{{ $edit->fr_why_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_why_subtitle">{{ __('Why Subtitle (Arabic)') }}</label>
                                        <textarea class="form-control" id="fr_why_subtitle" name="fr_why_subtitle" rows="3">{{ $edit->fr_why_subtitle }}</textarea>
                                    </div>
                                    <hr class="my-3">
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 1 (Arabic)') }}</label>
                                        <input type="text" class="form-control" name="fr_why_item_one" value="{{ $edit->fr_why_item_one }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 2 (Arabic)') }}</label>
                                        <input type="text" class="form-control" name="fr_why_item_two" value="{{ $edit->fr_why_item_two }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label>{{ __('Checklist Item 3 (Arabic)') }}</label>
                                        <input type="text" class="form-control" name="fr_why_item_three" value="{{ $edit->fr_why_item_three }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-3">
                                        <label for="why_image_one">{{ __('Why Image One (Coffee Beans)') }}</label>
                                        <input type="file" class="form-control" name="why_image_one" id="why_image_one">
                                        @if($edit->why_image_one)
                                            <img class="admin_image mt-2" src="{{ asset(aboutUsPage() . $edit->why_image_one) }}" style="max-height: 150px; border-radius: 8px;" />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-3">
                                        <label for="why_image_two">{{ __('Why Image Two (Latte Art)') }}</label>
                                        <input type="file" class="form-control" name="why_image_two" id="why_image_two">
                                        @if($edit->why_image_two)
                                            <img class="admin_image mt-2" src="{{ asset(aboutUsPage() . $edit->why_image_two) }}" style="max-height: 150px; border-radius: 8px;" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. CTA Section -->
                    <div class="card mb-4" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                        <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                            <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-bullhorn mr-2"></i> {{ __('5. CTA Section (دعوة للتفاعل)') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="input__group mb-3">
                                        <label for="en_cta_title">{{ __('CTA Title (English)') }}</label>
                                        <input type="text" class="form-control" id="en_cta_title" name="en_cta_title" value="{{ $edit->en_cta_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_cta_btn_crops">{{ __('Crops Button Text (English)') }}</label>
                                        <input type="text" class="form-control" id="en_cta_btn_crops" name="en_cta_btn_crops" value="{{ $edit->en_cta_btn_crops }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="en_cta_btn_expert">{{ __('Expert Button Text (English)') }}</label>
                                        <input type="text" class="form-control" id="en_cta_btn_expert" name="en_cta_btn_expert" value="{{ $edit->en_cta_btn_expert }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-3">
                                        <label for="fr_cta_title">{{ __('CTA Title (Arabic)') }}</label>
                                        <input type="text" class="form-control" id="fr_cta_title" name="fr_cta_title" value="{{ $edit->fr_cta_title }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_cta_btn_crops">{{ __('Crops Button Text (Arabic)') }}</label>
                                        <input type="text" class="form-control" id="fr_cta_btn_crops" name="fr_cta_btn_crops" value="{{ $edit->fr_cta_btn_crops }}">
                                    </div>
                                    <div class="input__group mb-3">
                                        <label for="fr_cta_btn_expert">{{ __('Expert Button Text (Arabic)') }}</label>
                                        <input type="text" class="form-control" id="fr_cta_btn_expert" name="fr_cta_btn_expert" value="{{ $edit->fr_cta_btn_expert }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-blue py-3 px-5" style="font-size: 16px; font-weight: bold; border-radius: 50px;">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
