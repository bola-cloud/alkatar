@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : __('Shipping & Return'))
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')

    @php
        $bannerTitle = $title ?? __('Shipping & Return');
        $locale = session('HTML_LANG', session('APP_LOCALE', app()->getLocale() ?? 'en'));
        $isAr = in_array($locale, ['ar', 'fr']);
        $content = CutomerServiceContent('shipping_return');
        $html = $content ? ($isAr ? ($content->fr_description ?? $content->en_description) : ($content->en_description ?? $content->fr_description)) : '';
    @endphp

    @include('front.partials.category_banner', ['title' => $bannerTitle])

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-4">{{ $bannerTitle }}</h1>
                <div class="newdesign-cms-content text-muted">
                    {!! clean($html) !!}
                </div>
            </div>
            {{-- optional image column --}}
            {{-- <div class="col-lg-4 d-none d-lg-block text-center">
                <img src="{{ asset('new-design/images/shipping.png') }}" alt="shipping" class="img-fluid"
                    style="max-height:420px; object-fit:cover;">
            </div> --}}
        </div>
    </div>

@endsection