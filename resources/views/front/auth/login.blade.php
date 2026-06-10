@extends('front.layouts.auth_layout')

@section('title', __('new_design.auth.login_title') . ' | ' . 'بن القطار')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
@endphp

<div class="w-full flex justify-start px-4 lg:pl-12 lg:pr-24">
    
    <div class="bg-[#F8F9F8]/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 lg:p-12 w-full max-w-[680px] {{ $isRtl ? 'lg:ml-auto' : 'lg:mr-auto' }}">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('assets/elketar/logo.png') }}" alt="Logo" class="h-16 object-contain">
        </div>

        <!-- Header -->
        <div class="mb-8 text-center {{ $isRtl ? 'lg:text-right' : 'lg:text-left' }}">
            <h1 class="text-3xl lg:text-[34px] font-black text-[#1A4231] mb-2 leading-tight">
                {{ __('new_design.auth.login_title') }}
            </h1>
            <p class="text-slate-600 text-sm font-semibold mt-1">
                {{ __('new_design.auth.login_subtitle') }}
            </p>
        </div>

        <!-- Alerts -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <ul class="list-disc {{ $isRtl ? 'list-inside' : 'list-outside pl-4' }} space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('user.sign.in.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Phone with Country Code dropdown -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}">
                    {{ __('new_design.auth.phone') }}
                </label>
                <div class="flex gap-2">
                    <!-- Country Code Select -->
                    <div class="w-1/3 relative">
                        <select name="country_code" class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800 appearance-none cursor-pointer {{ $isRtl ? 'text-right' : 'text-left' }}" required>
                            <option value="968" {{ old('country_code', '968') == '968' ? 'selected' : '' }}>OM (+968)</option>
                            <option value="966" {{ old('country_code') == '966' ? 'selected' : '' }}>SA (+966)</option>
                            <option value="971" {{ old('country_code') == '971' ? 'selected' : '' }}>AE (+971)</option>
                            <option value="974" {{ old('country_code') == '974' ? 'selected' : '' }}>QA (+974)</option>
                            <option value="973" {{ old('country_code') == '973' ? 'selected' : '' }}>BH (+973)</option>
                            <option value="965" {{ old('country_code') == '965' ? 'selected' : '' }}>KW (+965)</option>
                            <option value="20" {{ old('country_code') == '20' ? 'selected' : '' }}>EG (+20)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 {{ $isRtl ? 'left-3' : 'right-3' }} flex items-center text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    <!-- Phone Input -->
                    <div class="w-2/3 relative">
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="71234567" dir="ltr"
                               class="w-full px-5 py-3 rounded-xl border @error('phone') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800 text-left" required>
                    </div>
                </div>
                @error('country_code')
                    <p class="text-red-500 text-xs mt-1 {{ $isRtl ? 'text-right pr-1' : 'text-left pl-1' }}">{{ $message }}</p>
                @enderror
                @error('phone')
                    <p class="text-red-500 text-xs mt-1 {{ $isRtl ? 'text-right pr-1' : 'text-left pl-1' }}">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms -->
            <div class="flex items-center justify-start gap-2 pt-2">
                <input type="checkbox" id="terms" name="terms" checked class="w-4 h-4 rounded border-gray-300 text-[#1A4231] focus:ring-[#1A4231] cursor-pointer" required>
                <label for="terms" class="text-[12px] font-semibold text-gray-600 cursor-pointer">
                    {{ __('new_design.auth.terms_agree') }}
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-[#1A4231] text-white py-3.5 rounded-xl font-bold text-[15px] shadow-lg hover:bg-[#133224] transition-all flex items-center justify-center gap-3 mt-4">
                {{ __('new_design.auth.login_btn') }}
                <svg class="w-4 h-4 {{ app()->getLocale() == 'en' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </button>

        </form>

        <!-- Google Login -->
        <div class="mt-6 flex justify-center">
            <a href="{{ route('user.redirect_google') }}" class="bg-transparent border border-gray-300 px-6 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 transition-all">
                <span class="text-[11px] font-bold text-gray-700">Google</span>
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4">
            </a>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-[12px] font-semibold text-gray-500">
            {{ __('new_design.auth.no_account') }} 
            <a href="{{ route('user.sign.up') }}" class="text-[#1A4231] font-bold hover:underline">
                {{ __('new_design.auth.create_account') }}
            </a>
        </p>

    </div>
</div>
@endsection
