@extends('front.layouts.auth_layout')

@section('title', $method == 'email' ? __('Verify Your Email') : __('Verify Your WhatsApp') . ' | ' . 'بن القطار')

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
        <div class="mb-6 text-center {{ $isRtl ? 'lg:text-right' : 'lg:text-left' }}">
            <h1 class="text-3xl lg:text-[34px] font-black text-[#1A4231] mb-2 leading-tight">
                {{ $method == 'email' ? __('Verify Email') : __('Verify WhatsApp') }}
            </h1>
            <p class="text-slate-600 text-sm font-semibold mt-1">
                {{ __('Enter the 6-digit code sent to') }} <span dir="ltr" class="font-bold text-[#1A4231]">{{ $target }}</span>
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

        <!-- Form -->
        <form action="{{ route('user.verify.email.post') }}" method="POST" class="space-y-6">
            @csrf

            <!-- OTP input -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}">
                    {{ __('OTP Code') }}
                </label>
                <div class="relative">
                    <input type="text" id="otp" name="otp" placeholder="000000" maxlength="6" required autofocus autocomplete="off" dir="ltr"
                           class="w-full px-5 py-3 rounded-xl border @error('otp') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-bold text-gray-800 text-center tracking-[0.5em] placeholder:tracking-normal">
                    @error('otp')
                        <span class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </span>
                    @enderror
                </div>
                @error('otp')
                    <p class="text-red-500 text-xs mt-1 {{ $isRtl ? 'text-right pr-1' : 'text-left pl-1' }}">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-[#1A4231] text-white py-3.5 rounded-xl font-bold text-[15px] shadow-lg hover:bg-[#133224] transition-all flex items-center justify-center gap-3 mt-4">
                {{ __('Verify') }}
                <svg class="w-4 h-4 {{ app()->getLocale() == 'en' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </button>

        </form>

        <!-- Footer / Resend -->
        <p class="mt-8 text-center text-[12px] font-semibold text-gray-500">
            {{ __('Didn\'t receive the code?') }} 
            <a href="{{ route('user.resend.otp') }}" class="text-[#1A4231] font-bold hover:underline">
                {{ __('Resend OTP') }}
            </a>
        </p>

    </div>
</div>
@endsection
