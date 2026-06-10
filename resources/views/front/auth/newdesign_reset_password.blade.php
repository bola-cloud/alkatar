@extends('front.layouts.auth_layout')

@section('title', __('Reset Password') . ' | ' . 'بن القطار')

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
                {{ __('Reset Password') }}
            </h1>
            <p class="text-slate-600 text-sm font-semibold mt-1">
                {{ __('Update your password to secure your account.') }}
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
        <form action="{{ route('reset.password.post') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}">
                    {{ __('new_design.auth.email') }}
                </label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@farm.com" required
                           class="w-full px-5 py-3 rounded-xl border @error('email') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800">
                    @error('email')
                        <span class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </span>
                    @enderror
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 {{ $isRtl ? 'text-right pr-1' : 'text-left pl-1' }}">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}">
                    {{ __('new_design.auth.password') }}
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                           class="w-full px-5 py-3 rounded-xl border @error('password') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800">
                    @error('password')
                        <span class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </span>
                    @enderror
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1 {{ $isRtl ? 'text-right pr-1' : 'text-left pl-1' }}">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}">
                    {{ __('new_design.auth.confirm_password') }}
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required
                           class="w-full px-5 py-3 rounded-xl border border-gray-300 bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800">
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-[#1A4231] text-white py-3.5 rounded-xl font-bold text-[15px] shadow-lg hover:bg-[#133224] transition-all flex items-center justify-center gap-3 mt-4">
                {{ __('RESET PASSWORD') }}
                <svg class="w-4 h-4 {{ app()->getLocale() == 'en' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </button>

        </form>

    </div>
</div>
@endsection