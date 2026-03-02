@extends('admin.master', ['menu' => 'delivery_man', 'submenu' => 'delivery_man_list'])
@section('title', isset($title) ? $title : __('Edit Delivery Man'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Edit Delivery Man')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.dashboard')}}">{{__('Header.Dashboard')}}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.delivery_man')}}">{{__('Delivery Man List')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Edit Delivery Man')}}</li>
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
                                        <form action="{{ route('admin.delivery_man.update', $deliveryMan->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" required value="{{ $deliveryMan->name }}" placeholder="{{ __('Enter Name') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label>{{ __('Email') }}</label>
                                                <input type="email" name="email" class="form-control" value="{{ $deliveryMan->email }}" placeholder="{{ __('Enter Email') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control" required value="{{ $deliveryMan->phone }}" placeholder="{{ __('Enter Phone Number') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label>{{ __('Password') }} <small>({{ __('Leave blank if you do not want to change') }})</small></label>
                                                <input type="password" name="password" class="form-control" placeholder="{{ __('Enter New Password') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <div class="custom-control custom-switch d-flex align-items-center">
                                                    <input type="checkbox" id="status" name="status" {{ $deliveryMan->status ? 'checked' : '' }} style="width: 20px; height: 20px; margin-right: 10px;">
                                                    <label for="status" class="mb-0">{{ __('Active Status') }}</label>
                                                </div>
                                            </div>
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
@endsection
