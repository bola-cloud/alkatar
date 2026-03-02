@extends('admin.master', ['menu' => 'subscriptions', 'submenu' => 'subscriptions'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Subscribers') }}: {{ $subscription->name }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.subscriptions') }}">{{ __('Subscriptions') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Subscribers') }}</li>
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $userSub)
                                    <tr>
                                        <td>{{ $userSub->user->name ?? 'N/A' }}</td>
                                        <td>{{ $userSub->user->email ?? 'N/A' }}</td>
                                        <td>{{ $userSub->user->phone ?? 'N/A' }}</td>
                                        <td>{{ $userSub->start_at->format('Y-m-d') }}</td>
                                        <td>{{ $userSub->end_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if($userSub->status)
                                                <span class="badge badge-pill badge-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge badge-pill badge-danger">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection