@extends('admin.master', ['menu' => 'delivery_man', 'submenu' => 'delivery_man_list'])
@section('title', isset($title) ? $title : __('Delivery Man List'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Delivery Man List')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('admin.dashboard')}}">{{__('Header.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Delivery Man List')}}</li>
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
                        <a href="{{ route('admin.delivery_man.create') }}" class="btn btn-md btn-info"><i
                                class="fas fa-plus"></i>
                            {{ __('Add New') }}</a>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="delivery_man_table" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('post_scripts')
    <script>
        $(document).ready(function () {
            $('#delivery_man_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.delivery_man') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                    },
                    search: "_INPUT_",
                    searchPlaceholder: "{{ __('Search...') }}",
                    lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
                    zeroRecords: "{{ __('No data available in table') }}",
                    info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                    infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                    infoFiltered: "({{ __('filtered from') }} _MAX_ {{ __('total entries') }})",
                }
            });
        });
    </script>
@endpush