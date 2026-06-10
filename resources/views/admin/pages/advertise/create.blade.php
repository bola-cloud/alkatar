@extends('admin.master', ['menu' => 'advertise'])
@section('title', isset($title) ? $title : '')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{ __('Add Advertise') }}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Advertise') }}</li>
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
                        <form enctype="multipart/form-data" method="POST" action="{{ route('admin.advertise.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{ __('Hero Image') }}</h2>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="image">{{ __('Hero Image')}} (1600x430)</label>
                                            <input type="file" class="form-control" name="image" id="image">
                                            <small class="form-text text-muted">{{ __('Or provide a public image path in the field below (e.g. new-design/images/bannar-big.png)') }}</small>
                                        </div>
                                        {{-- image_path removed to enforce uploaded images only --}}
                                        <div class="input__group mb-25">
                                            <img id="target1" class="admin_image" src="" style="display:none; max-width:100%; height:auto;" />
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
                                            <input type="text" class="form-control" name="en_title" id="en_title">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="en_small_description">{{ __('Small Description (EN)')}}</label>
                                            <textarea class="form-control" name="en_small_description" id="en_small_description" rows="2"></textarea>
                                            <small class="text-muted">{{ __('Suggested: two short lines, e.g. "Sale up to 30% OFF\nFree shipping on all your order."') }}</small>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="en_subtitle">{{ __('Subtitle (EN)')}}</label>
                                            <input type="text" class="form-control" name="en_subtitle" id="en_subtitle">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="ar_title">{{ __('Title (AR)')}}</label>
                                            <input type="text" class="form-control" name="ar_title" id="ar_title">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="ar_subtitle">{{ __('Subtitle (AR)')}}</label>
                                            <input type="text" class="form-control" name="ar_subtitle" id="ar_subtitle">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="ar_small_description">{{ __('Small Description (AR)')}}</label>
                                            <textarea class="form-control" name="ar_small_description" id="ar_small_description" rows="2"></textarea>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="link">{{ __('Link')}}</label>
                                            <input type="text" class="form-control" name="link" id="link">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="display_order">{{ __('Display Order')}}</label>
                                            <input type="number" class="form-control" name="display_order" id="display_order" value="0">
                                        </div>
                                        <div class="input__group mb-25">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="status" id="status" checked>
                                                <label class="form-check-label" for="status">{{ __('Active') }}</label>
                                            </div>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="location">{{ __('Location / Page') }}</label>
                                            <select class="form-control" name="location" id="location">
                                                <option value="hero" {{ request('location') == 'hero' ? 'selected' : '' }}>{{ __('Homepage Hero Slider') }}</option>
                                                <option value="coffee_crops" {{ request('location') == 'coffee_crops' ? 'selected' : '' }}>{{ __('Coffee Crops Slider') }}</option>
                                                <option value="technical_tools" {{ request('location') == 'technical_tools' ? 'selected' : '' }}>{{ __('Technical Tools Slider') }}</option>
                                            </select>
                                        </div>
                                        <div class="input__button">
                                            <button type="submit" class="btn btn-blue">{{ __('Add')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            (function(){
                                var input = document.getElementById('image');
                                var img = document.getElementById('target1');
                                if(input){
                                    input.addEventListener('change', function(e){
                                        var file = e.target.files && e.target.files[0];
                                        if(!file){ img.style.display = 'none'; img.src = ''; return; }
                                        var reader = new FileReader();
                                        reader.onload = function(ev){
                                            img.src = ev.target.result;
                                            img.style.display = '';
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
