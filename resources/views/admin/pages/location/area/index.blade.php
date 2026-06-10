@extends('admin.master', ['menu' => 'delivery_charge', 'submenu' => 'areas'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Areas')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Areas')}}</li>
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
                        <button data-bs-toggle="modal" data-bs-target="#createAreaModal"
                            class="btn btn-md btn-info">{{ __('Add Area')}}</button>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="AreaTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>{{ __('Name (EN)')}}</th>
                                <th>{{ __('Name (AR)')}}</th>
                                <th>{{ __('Wilayat')}}</th>
                                <th>{{ __('Governorate')}}</th>
                                <th>{{ __('Charge')}}</th>
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
    <div class="modal fade" id="createAreaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">{{__('Add Area')}}</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('admin.location.area.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="input__group mb-25">
                            <label>{{__('Wilayat')}}</label>
                            <select name="city_id" required>
                                <option value="">{{__('---Select Wilayat---')}}</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->id}}" {{$selected_city == $city->id ? 'selected' : ''}}>
                                        {{ langConverter($city->name_en, $city->name_fr) }} 
                                        ({{ langConverter($city->state->name_en ?? '', $city->state->name_fr ?? '') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (EN)')}}</label>
                            <input type="text" name="name_en" required placeholder="{{ __('Area Name EN') }}">
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (AR)')}}</label>
                            <input type="text" name="name_fr" required placeholder="{{ __('Area Name AR') }}">
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Charge')}}</label>
                            <input type="number" step="0.001" name="charge" required placeholder="{{ __('Charge') }}">
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
    @foreach ($areas as $area)
        <div class="modal fade" id="editAreaModal{{$area->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">{{__('Edit Area')}}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{route('admin.location.area.update', $area->id)}}">
                        @csrf
                        <div class="modal-body">
                            <div class="input__group mb-25">
                                <label>{{__('Wilayat')}}</label>
                                <select name="city_id" required>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" {{$area->city_id == $city->id ? 'selected' : ''}}>
                                            {{ langConverter($city->name_en, $city->name_fr) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Name (EN)')}}</label>
                                <input type="text" name="name_en" value="{{$area->name_en}}" required>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Name (AR)')}}</label>
                                <input type="text" name="name_fr" value="{{$area->name_fr}}" required>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Charge')}}</label>
                                <input type="number" step="0.001" name="charge" value="{{$area->deliveryCharge->charge ?? 0}}" required>
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
            $('#AreaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.location.area.list') }}",
                    data: function (d) {
                        d.city_id = "{{ $selected_city }}";
                    }
                },
                columns: [
                    { data: 'name_en', name: 'name_en' },
                    { data: 'name_ar', name: 'name_ar' },
                    { data: 'city_name', name: 'city.name_en' },
                    { data: 'state_name', name: 'city.state.name_en' },
                    { data: 'charge', name: 'deliveryCharge.charge' },
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
