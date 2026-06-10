@extends('admin.master', ['menu' => 'delivery_charge', 'submenu' => 'governorates'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Governorates')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Governorates')}}</li>
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
                        <button data-bs-toggle="modal" data-bs-target="#createStateModal"
                            class="btn btn-md btn-info">{{ __('Add Governorate')}}</button>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="StateTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>{{ __('Name (EN)')}}</th>
                                <th>{{ __('Name (AR)')}}</th>
                                <th>{{ __('Country')}}</th>
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
    <div class="modal fade" id="createStateModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">{{__('Add Governorate')}}</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('admin.location.state.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="input__group mb-25">
                            <label>{{__('Country')}}</label>
                            <select name="country_id" required>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{$country->name_en == 'Oman' ? 'selected' : ''}}>
                                        {{ langConverter($country->name_en, $country->name_fr) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (EN)')}}</label>
                            <input type="text" name="name_en" required placeholder="{{ __('Governorate Name EN') }}">
                        </div>
                        <div class="input__group mb-25">
                            <label>{{__('Name (AR)')}}</label>
                            <input type="text" name="name_fr" required placeholder="{{ __('Governorate Name AR') }}">
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
    @foreach ($states as $state)
        <div class="modal fade" id="editStateModal{{$state->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">{{__('Edit Governorate')}}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{route('admin.location.state.update', $state->id)}}">
                        @csrf
                        <div class="modal-body">
                            <div class="input__group mb-25">
                                <label>{{__('Name (EN)')}}</label>
                                <input type="text" name="name_en" value="{{$state->name_en}}" required>
                            </div>
                            <div class="input__group mb-25">
                                <label>{{__('Name (AR)')}}</label>
                                <input type="text" name="name_fr" value="{{$state->name_fr}}" required>
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
            $('#StateTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.location.state.list') }}",
                columns: [
                    { data: 'name_en', name: 'name_en' },
                    { data: 'name_ar', name: 'name_ar' },
                    { data: 'country.name_en', name: 'country.name_en' },
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
