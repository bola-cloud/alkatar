@extends('admin.master', ['menu' => 'advertise'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Edit Advertise')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Advertise')}}</li>
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
                                <form enctype="multipart/form-data" method="POST" action="{{route('admin.advertise.update')}}" class="d-flex">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$edit->id}}">
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('Hero Image') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="image">{{ __('Hero Image')}} (1600x430)</label>
                                                <input type="file" class="form-control" name="image" id="image">
                                            </div>
                                            @php
                                                $heroUrl = '';
                                                if (!empty($edit->image) && file_exists(public_path($edit->image))) {
                                                    $heroUrl = asset($edit->image);
                                                } elseif (!empty($edit->image)) {
                                                    $heroUrl = asset(PromotionImage() . $edit->image);
                                                }
                                            @endphp
                                            <div class="input__group mb-25">
                                                <img class="admin_image" src="{{ $heroUrl ?: asset(PromotionImage().$edit->Image_One) }}" id="target1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('Content') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="en_title">{{ __('Title (EN)')}}</label>
                                                <input type="text" class="form-control" name="en_title" id="en_title" value="{{ $edit->en_title ?? '' }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="en_small_description">{{ __('Small Description (EN)')}}</label>
                                                <textarea class="form-control" name="en_small_description" id="en_small_description" rows="2">{{ $edit->en_small_description ?? '' }}</textarea>
                                                <small class="text-muted">{{ __('Suggested: two short lines, e.g. "Sale up to 30% OFF\nFree shipping on all your order."') }}</small>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="en_subtitle">{{ __('Subtitle (EN)')}}</label>
                                                <input type="text" class="form-control" name="en_subtitle" id="en_subtitle" value="{{ $edit->en_subtitle ?? '' }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="ar_title">{{ __('Title (AR)')}}</label>
                                                <input type="text" class="form-control" name="ar_title" id="ar_title" value="{{ $edit->ar_title ?? $edit->fr_title ?? '' }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="ar_subtitle">{{ __('Subtitle (AR)')}}</label>
                                                <input type="text" class="form-control" name="ar_subtitle" id="ar_subtitle" value="{{ $edit->ar_subtitle ?? $edit->fr_subtitle ?? '' }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="ar_small_description">{{ __('Small Description (AR)')}}</label>
                                                <textarea class="form-control" name="ar_small_description" id="ar_small_description" rows="2">{{ $edit->ar_small_description ?? '' }}</textarea>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="link">{{ __('Link')}}</label>
                                                <input type="text" class="form-control" name="link" id="link" value="{{ $edit->link ?? $edit->Link_One ?? '' }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="display_order">{{ __('Display Order')}}</label>
                                                <input type="number" class="form-control" name="display_order" id="display_order" value="{{ $edit->display_order ?? 0 }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="status" id="status" {{ ($edit->status ?? 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status">{{ __('Active') }}</label>
                                                </div>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="location">{{ __('Location / Page') }}</label>
                                                <select class="form-control" name="location" id="location">
                                                    <option value="hero" {{ ($edit->location ?? 'hero') == 'hero' ? 'selected' : '' }}>{{ __('Homepage Hero Slider') }}</option>
                                                    <option value="coffee_crops" {{ ($edit->location ?? 'hero') == 'coffee_crops' ? 'selected' : '' }}>{{ __('Coffee Crops Slider') }}</option>
                                                    <option value="technical_tools" {{ ($edit->location ?? 'hero') == 'technical_tools' ? 'selected' : '' }}>{{ __('Technical Tools Slider') }}</option>
                                                </select>
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <script>
                                (function(){
                                    var input = document.getElementById('image');
                                    var img = document.getElementById('target1');
                                    if(input){
                                        input.addEventListener('change', function(e){
                                            var file = e.target.files && e.target.files[0];
                                            if(!file){ return; }
                                            var reader = new FileReader();
                                            reader.onload = function(ev){
                                                if(img){ img.src = ev.target.result; img.style.display = ''; }
                                            };
                                            reader.readAsDataURL(file);
                                        });
                                    }
                                })();
                            </script>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
