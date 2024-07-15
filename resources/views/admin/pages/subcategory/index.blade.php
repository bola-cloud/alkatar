@extends('admin.master', ['menu' => 'catbad', 'submenu' => 'subcategory'])
@section('title', isset($title) ? $title : '')
@section('content')
<div id="table-url" data-url="{{ route('admin.subcategory') }}"></div>
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{__('Subcategory')}}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Subcategory')}}</li>
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
                    <a href="{{route('admin.subcategory.create')}}"
                        class="btn btn-md btn-info">{{ __('Add Subcategory')}}</a>
                </div>
            </div>
            <div class="customers__table">
                <table id="SubcategoryTable" class="row-border data-table-filter table-style">
                    <thead>
                        <tr>
                            <th>{{ __('Subcategory Name')}}</th>
                            <th>{{ __('Subcategory Arabic')}}</th>
                            <th>{{__('Parent Category')}}</th>
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
            $('#SubcategoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('admin.subcategory')}}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'name_ar', name: 'name_ar' },
                    { data: 'category_id', name: 'category_id' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>

@endpush
@endsection