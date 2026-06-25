@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'gift_card_packages'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Gift Card Packages') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Gift Card Packages') }}</li>
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
                    <div class="table__tools">
                        <a href="{{ route('admin.gift_card_packages.create') }}"
                            class="btn btn-sm btn-primary">{{ __('Add Package') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Key') }}</th>
                                    <th>{{ __('Name (AR)') }}</th>
                                    <th>{{ __('Name (EN)') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->id }}</td>
                                        <td><code>{{ $package->key }}</code></td>
                                        <td>{{ $package->name_ar }}</td>
                                        <td>{{ $package->name_en }}</td>
                                        <td>{{ number_format($package->price, 3) }} {{ __('OMR') }}</td>
                                        <td>
                                            @if($package->status)
                                                <span class="badge badge-pill"
                                                    style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i
                                                        class="fas fa-check-circle mr-1"></i>{{ __('Active') }}</span>
                                            @else
                                                <span class="badge badge-pill"
                                                    style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i
                                                        class="fas fa-times-circle mr-1"></i>{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action__buttons" style="display: flex; gap: 8px;">
                                                <a href="{{ route('admin.gift_card_packages.edit', $package->id) }}" class="btn-action"
                                                    title="{{ __('Edit') }}"
                                                    style="padding: 6px 10px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="{{ route('admin.gift_card_packages.delete', $package->id) }}"
                                                    onclick="return confirm('{{ __('Are you sure?') }}')"
                                                    class="btn-action delete" title="{{ __('Delete') }}"
                                                    style="padding: 6px 10px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i
                                                        class="fas fa-trash-alt"></i></a>
                                            </div>
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
