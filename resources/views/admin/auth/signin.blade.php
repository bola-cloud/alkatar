@extends('front.layouts.auth_layout')

@section('title', ($allsettings['app_title'] ?? 'Al-Katar') . ' - ' . __('Admin Login'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
@endphp

<div class="w-full flex justify-center px-4">
    
    <div class="bg-[#F8F9F8]/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 lg:p-12 w-full max-w-[580px]">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('assets/elketar/logo.png') }}" alt="Logo" class="h-16 object-contain">
        </div>

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black text-[#1A4231] mb-2 leading-tight">
                {{ __('Admin Sign In') }}
            </h1>
            <p class="text-slate-600 text-sm font-semibold mt-1">
                {{ __('Admin Panel Access') }}
            </p>
        </div>

        <!-- Sessions -->
        @if(session('error'))
        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold mb-5 {{ $isRtl ? 'text-right' : 'text-left' }}">
            {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm font-bold mb-5 {{ $isRtl ? 'text-right' : 'text-left' }}">
            {{ session('success') }}
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}" for="email">
                    {{ __('Email Address') }}
                </label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ env('APP_DEMO') == true ? 'admin@gmail.com' : old('email') }}" required
                           class="w-full px-5 py-3.5 rounded-xl border @error('email') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800">
                    @error('email')
                        <span class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }} relative mt-4">
                <label class="block text-[13px] font-bold text-gray-700 mb-2 {{ $isRtl ? 'pr-1' : 'pl-1' }}" for="password">
                    {{ __('Password') }}
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" value="{{ env('APP_DEMO') == true ? '123456' : '' }}" required placeholder="••••••••" 
                           class="w-full px-5 py-3.5 rounded-xl border @error('password') border-red-500 @else border-gray-300 @enderror bg-transparent outline-none focus:border-[#1A4231] focus:ring-1 focus:ring-[#1A4231] transition-all text-sm font-semibold text-gray-800">
                    @error('password')
                        <span class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-10a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </span>
                    @enderror
                    <button type="button" onclick="togglePassword()" class="absolute {{ $isRtl ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-xs font-bold text-[#1A4231] hover:underline">
                        {{ __('Show') }}
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-start gap-2 pt-2">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#1A4231] focus:ring-[#1A4231] cursor-pointer">
                <label for="remember" class="text-[12px] font-semibold text-gray-600 cursor-pointer">
                    {{ __('Remember me') }}
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#1A4231] text-white py-3.5 rounded-xl font-bold text-[15px] shadow-lg hover:bg-[#133224] transition-all flex items-center justify-center gap-3 mt-4">
                {{ __('Sign In') }}
                <svg class="w-4 h-4 {{ app()->getLocale() == 'en' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </button>

        </form>

    </div>
</div>

<script>
    function togglePassword(){
        var p = document.getElementById('password');
        if(p.type === 'password'){ 
            p.type = 'text'; 
        } else { 
            p.type = 'password'; 
        }
    }
</script>

@include('sweetalert::alert')

@endsection
