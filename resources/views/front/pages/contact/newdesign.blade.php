@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : 'Contact Us')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')

{{-- reuse category banner for layout consistency --}}
@include('front.partials.category_banner', ['title' => isset($title) ? $title : __('Contact Us')])

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex gap-4 align-items-start mb-4" style="flex-wrap:wrap;">
                {{-- Left cards (contact info) --}}
                <div style="flex:0 0 260px;">
                    <div class="bg-white p-4 rounded shadow-sm mb-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-geo-alt-fill" style="font-size:22px;color:#f6a22b"></i>
                        </div>
                        <div class="text-muted text-center">{{ $allsettings['address'] ?? '' }}</div>
                        <div class="text-muted text-center mt-2">{{ $allsettings['state'] ?? '' }}, {{ $allsettings['country'] ?? '' }}</div>
                    </div>

                    <div class="bg-white p-4 rounded shadow-sm mb-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-envelope" style="font-size:22px;color:#f6a22b"></i>
                        </div>
                        <div class="text-muted text-center">{{ $allsettings['email'] ?? '' }}</div>
                        <div class="text-muted text-center mt-2">{{ $allsettings['alt_email'] ?? '' }}</div>
                    </div>

                    <div class="bg-white p-4 rounded shadow-sm">
                        <div class="text-center mb-3">
                            <i class="bi bi-telephone" style="font-size:22px;color:#f6a22b"></i>
                        </div>
                        <div class="text-muted text-center">{{ $allsettings['call_us'] ?? '' }}</div>
                        <div class="text-muted text-center mt-2">{{ $allsettings['call_us_alt'] ?? '' }}</div>
                    </div>
                </div>

                {{-- Right: large contact card with form --}}
                <div style="flex:1;min-width:300px;">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h3 class="mb-2" style="font-weight:700">{{ __('We Welcome Your Comments And Suggestions') }}</h3>
                        <p class="text-muted mb-3">{{ __('If you want to get started adding orders and need help? Feel free to contact us.') }}</p>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="post" action="{{ route('contact.us.store') }}">
                            @csrf
                            <input type="text" name="spam_field" style="display:none;">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="firstname" class="form-control" placeholder="{{ __('Full name') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}">
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="phone" class="form-control" placeholder="{{ __('Enter your phone') }}">
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="subject" class="form-control" placeholder="{{ __('Subjects') }}">
                                </div>
                                <div class="col-md-12">
                                    <textarea name="message" rows="5" class="form-control" placeholder="{{ __('Write your message...') }}"></textarea>
                                </div>
                                <div class="col-md-12 text-start">
                                    <button class="btn" style="background:#9fc93d;color:#fff;border-radius:30px;padding:8px 24px">{{ __('Send Message') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- Map area --}}
            <div class="mt-4 rounded overflow-hidden" style="height:320px;">
                {{-- Use admin-provided iframe when available; otherwise show Muscat map embed --}}
                @if(!empty($allsettings['map_iframe']))
                    {!! $allsettings['map_iframe'] !!}
                @else
                    <iframe
                        width="100%"
                        height="100%"
                        style="border:0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Muscat+Oman&z=12&output=embed">
                    </iframe>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
