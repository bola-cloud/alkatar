@extends('admin.master', ['menu' => 'delivery_charge'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Manage Areas for')}} {{ langConverter($city->name_en, null, $city->name_ar) }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.country_dc_list')}}">{{__('Delivery Charge')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Manage Areas')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <form action="{{ route('admin.city_areas_update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="city_id" value="{{ $city->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Area Name') }}</th>
                                    <th>{{ __('Delivery Charge') }} ({{ currencySymbol()[currency()] ?? 'OMR' }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($areas as $area)
                                    <tr>
                                        <td>
                                            {{ langConverter($area->name_en, null, $area->name_ar) }}
                                        </td>
                                        <td>
                                            <input type="number" step="0.001" min="0" name="charges[{{ $area->id }}]"
                                                class="form-control" value="{{ $charges[$area->id] ?? 0 }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('Update All Charges') }}</button>
                        <a href="{{ route('admin.country_dc_list') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection