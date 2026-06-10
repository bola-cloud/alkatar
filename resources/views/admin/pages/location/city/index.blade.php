@extends('admin.master', ['menu' => 'delivery_charge', 'submenu' => 'wilayats'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Wilayats')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Wilayats')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="item-title">
                    <div class="col-xs-6">
                        <button data-bs-toggle="modal" data-bs-target="#createCityModal"
                            class="btn btn-md btn-info">{{ __('Add Wilayat')}}</button>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="CityTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>{{ __('Name (EN)')}}</th>
                                <th>{{ __('Name (AR)')}}</th>
                                <th>{{ __('Governorate')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createCityModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">{{__('Add Wilayat')}}</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('admin.location.city.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="input__group mb-25">
                            <label>{{__('Governorate')}}</label>
                            <select name="state_id" required>
                                <option value="">{{__('---Select Governorate---')}}</option>
                                @foreach($states as $state)
                                    <option value="{{$state->id}}" {{$selected_state == $state->id ? 'selected' : ''}}>
                                        {{ langConverter($state->name_en, $state->name_fr) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (EN)')}}</label>
                            <input type="text" name="name_en" required placeholder="{{ __('Wilayat Name EN') }}">
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (AR)')}}</label>
                            <input type="text" name="name_fr" required placeholder="{{ __('Wilayat Name AR') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach ($cities as $city)
        <div class="modal fade" id="editCityModal{{$city->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">{{__('Edit Wilayat')}}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{route('admin.location.city.update', $city->id)}}">
                        @csrf
                        <div class="modal-body">
                            <div class="input__group mb-25">
                                <label>{{__('Governorate')}}</label>
                                <select name="state_id" required>
                                    @foreach($states as $state)
                                        <option value="{{$state->id}}" {{$city->state_id == $state->id ? 'selected' : ''}}>
                                            {{ langConverter($state->name_en, $state->name_fr) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Name (EN)')}}</label>
                                <input type="text" name="name_en" value="{{$city->name_en}}" required>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Name (AR)')}}</label>
                                <input type="text" name="name_fr" value="{{$city->name_fr}}" required>
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
    <script>
        $(document).ready(function() {
            $('#CityTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.location.city.list') }}",
                    data: function (d) {
                        d.state_id = "{{ $selected_state }}";
                    }
                },
                columns: [
                    { data: 'name_en', name: 'name_en' },
                    { data: 'name_ar', name: 'name_ar' },
                    { data: 'state_name', name: 'state.name_en' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $(document).on('click', '.delete-item', function(e) {
                if (!confirm("Are you sure you want to delete this item? All related data will be deleted.")) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
@endsection
