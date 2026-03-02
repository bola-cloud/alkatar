@extends('front.layouts.new_design_layout')
@section('title', __('404 - Page Not Found'))
@section('description', __('The page you are looking for could not be found.'))
@section('content')

@php
    $imgPng = asset('new-design/images/error.png');
    $imgSvg = asset('new-design/images/error.svg');
@endphp

<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="my-5">
                <img src="{{ $imgPng }}" alt="404" class="img-fluid mx-auto d-block" style="max-width:720px;" onerror="this.onerror=null;this.src='{{ $imgSvg }}'" />
            </div>

            <h1 class="h2 fw-bold mt-4">{{ __('Oops! page not found') }}</h1>
            <p class="text-muted my-3">{{ __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') }}</p>

            <a href="{{ url('/') }}" class="btn btn-success mt-3">{{ __('Back to Home') }}</a>
        </div>
    </div>
</div>

@endsection
@extends('errors.layout')
@section('title', __('Error'))
@section('content')
    <!-- breadcrumb area start here  -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-wrap text-center">
                <h2 class="page-title">{{__('Error')}}</h2>
                <ul class="breadcrumb-pages">
                    <li class="page-item"><a class="page-item-link" href="{{route('front')}}">{{__('Home')}}</a></li>
                    <li class="page-item">{{__('Error')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end here  -->

    <!-- Error Page area start here  -->
    <div class="error-page-area section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="fw-bold">{{__('ERROR 404 NOT FOUND')}}</h1>
                    <p>
                        {{__('You may have mis-typed the URL.
                        Or the page has been removed.Actually, there is nothing to see here. Click on the button below
                        to do something, Thanks!')}}
                    </p>
                    <a href="{{ url('/') }}" class="primary-btn">{{__('Back to Home')}}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Error Page area end here  -->
@endsection
