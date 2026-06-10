@extends('admin.master', ['menu' => 'delivery_charge'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div id="table-url" data-url="{{ route('admin.country_dc_list') }}" data-locale="{{ app()->getLocale() }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Delivery Charge')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Delivery Charge')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table">
                    <table id="BlogTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>{{ __('Governorate')}}</th>
                                <th>{{ __('Wilayat')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

    @foreach ($delivery_charges as $dc)
        <div class="modal fade" id="editModal{{$dc->id}}" tabindex="-1" role="dialog"
            aria-labelledby="editModalTitle{{$dc->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="editModalLongTitle">{{__('Edit')}}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form enctype="multipart/form-data" method="POST"
                        action="{{route('admin.country_dc_update', encrypt($dc->id))}}">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="country" value="Oman" />
                            <div class="input__group mb-25">
                                <label for="state_edit">{{__('Governorate')}}</label>
                                <select name="state_id" id="state_edit_{{$dc->id}}" required>
                                    <option value="">{{__('---Select Governorate---')}}</option>
                                    @foreach ($states as $state)
                                        <option value="{{$state->id}}" {{$dc->state_id == $state->id ? 'selected' : ''}}>
                                            {{$state->name_en}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label for="city_edit">{{__('Wilayat')}}</label>
                                <select name="city_id" id="city_edit_{{$dc->id}}" required>
                                    <option value="{{$dc->city_id}}">
                                        {{ langConverter(optional($dc->city)->name_en, optional($dc->city)->name_fr) }}
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="area_id" value="">
                            <div class="input__group mb-25">
                                <label for="charge_edit">{{ __('Charge')}}</label>
                                <input type="number" min="0" step="0.001" name="charge" id="charge_edit_{{$dc->id}}"
                                    placeholder="{{__('Delivery Charge')}}" value="{{$dc->charge}}" required>
                            </div>
                            <div class="input__group mb-25">
                                <label for="status">{{__('Status')}}</label>
                                <select name="status" id="status" required>
                                    <option value="{{ACTIVE}}" {{$dc->status == ACTIVE ? 'selected' : ''}}>{{__('Active')}}
                                    </option>
                                    <option value="{{INACTIVE}}" {{$dc->status == INACTIVE ? 'selected' : ''}}>{{__('Inactive')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('post_scripts')
        <script src="{{asset('backend/js/admin/datatables/delivery-charge/country.js')}}"></script>
        <script>
            $(document).ready(function () {
                // Function to load cities
                function loadCities(stateId, citySelectId, areaSelectId) {
                    if (stateId) {
                        $.ajax({
                            url: '/get-cities-by-state/' + stateId,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $(citySelectId).empty();
                                $(citySelectId).append('<option value="">{{ __("---Select Wilayat---") }}</option>');
                                $.each(data, function (key, value) {
                                    $(citySelectId).append('<option value="' + value.id + '">' + value.name_en + '</option>');
                                });
                                // Reset Area
                                if (areaSelectId) {
                                    $(areaSelectId).empty();
                                    $(areaSelectId).append('<option value="">{{ __("---Select Area---") }}</option>');
                                }
                            }
                        });
                    } else {
                        $(citySelectId).empty();
                        $(citySelectId).append('<option value="">{{ __("---Select Wilayat---") }}</option>');
                        if (areaSelectId) {
                            $(areaSelectId).empty();
                            $(areaSelectId).append('<option value="">{{ __("---Select Area---") }}</option>');
                        }
                    }
                }

                // Function to load areas
                function loadAreas(cityId, areaSelectId) {
                    if (cityId) {
                        $.ajax({
                            url: '/get-areas-by-city/' + cityId,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $(areaSelectId).empty();
                                $(areaSelectId).append('<option value="">{{ __("---Select Area---") }}</option>');
                                $.each(data, function (key, value) {
                                    $(areaSelectId).append('<option value="' + value.id + '">' + value.name_en + '</option>');
                                });
                            }
                        });
                    } else {
                        $(areaSelectId).empty();
                        $(areaSelectId).append('<option value="">{{ __("---Select Area---") }}</option>');
                    }
                }

                $('#state').on('change', function () {
                    var stateId = $(this).val();
                    loadCities(stateId, '#city', '#area');
                });

                $('#city').on('change', function () {
                    var cityId = $(this).val();
                    loadAreas(cityId, '#area');
                });

                @foreach($delivery_charges as $dc)
                    $('#state_edit_{{$dc->id}}').on('change', function () {
                        var stateId = $(this).val();
                        loadCities(stateId, '#city_edit_{{$dc->id}}', '#area_edit_{{$dc->id}}');
                    });

                    $('#city_edit_{{$dc->id}}').on('change', function () {
                        var cityId = $(this).val();
                        loadAreas(cityId, '#area_edit_{{$dc->id}}');
                    });
                @endforeach
                                                });
        </script>
    @endpush
@endsection