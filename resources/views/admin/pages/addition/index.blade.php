@extends('admin.master', ['menu' => 'catbad', 'submenu' => 'addition_list'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Addition List')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Addition')}}</li>
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
                    <table id="AdditionTable" class="row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Name (EN)')}}</th>
                            <th>{{ __('Name (AR)')}}</th>
                            <th>{{ __('Product')}}</th>
                            <th>{{ __('Price')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Icon')}}</th>
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
    <!--Row-->
    @push('post_scripts')
        <script>
            $(document).ready(function () {
                'use strict';
                $('#AdditionTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('admin.physical.product.addition.index')}}',
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'name_ar', name: 'name_ar'},
                        {data: 'product', name: 'product'},
                        {data: 'price', name: 'price'},
                        {data: 'status', name: 'status'},
                        {data: 'icon', name: 'icon'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@endsection