@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-account" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">My account</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-orders" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">My Orders</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">My address</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="tab-account">
                        <div class="row gx-4">
                            <div class="col-lg-4 mb-4">
                                <div class="card p-4 text-center">
                                    <img id="target1" src="{{ file_exists(AdminProfilePicture() . Auth::user()->image) ? (isset(Auth::user()->image) ? asset(AdminProfilePicture() . Auth::user()->image) : Avatar::create(Auth::user()->name)->toBase64()) : Auth::user()->image }}" alt="avatar" class="rounded-circle" width="120">
                                    <h5 class="mt-3">{{ $user->name }}</h5>
                                    <p class="text-muted">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card p-4">
                                    <div class="mb-3">
                                        <p class="mb-2"><strong>Profile editor moved</strong></p>
                                        <p class="text-muted">To avoid duplicate forms and tab conflicts, the profile editor is now available on the unified profile page. Please use the "My account" tab on the main profile page to edit your details.</p>
                                        <p>
                                            <a href="{{ route('user.profile') }}" class="btn btn-outline-success">Open Profile Editor</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="tab-orders">
                        <div class="card p-4">
                            <p class="mb-0">Orders list will appear here.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="tab-address">
                        <div class="card p-4">
                            <p class="mb-0">Address management will appear here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
