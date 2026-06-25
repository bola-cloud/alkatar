@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'custom_box_templates'])
@section('title', isset($title) ? $title : '')
@section('content')

    <div id="table-url" data-url="{{ route('admin.custom_box_templates.index') }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Custom Box Templates')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Templates')}}</li>
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
                        <a href="{{route('admin.custom_box_templates.create')}}" class="btn btn-md btn-info">{{ __('Add Custom Box Template')}}</a>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="TemplatesTable" class="row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Name EN')}}</th>
                            <th>{{ __('Name AR')}}</th>
                            <th>{{ __('Color code')}}</th>
                            <th>{{ __('Price')}}</th>
                            <th>{{ __('Status')}}</th>
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

    @push('post_scripts')
        <script>
            (function($) {
                "use strict";
                $(document).ready(function () {
                    $('#TemplatesTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: $('#table-url').data("url"),
                        columns: [
                            {
                                data: 'name_en',
                                name: 'name_en'
                            },
                            {
                                data: 'name_ar',
                                name: 'name_ar'
                            },
                            {
                                data: 'color_code',
                                name: 'color_code'
                            },
                            {
                                data: 'price',
                                name: 'price'
                            },
                            {
                                data: 'is_active',
                                name: 'is_active'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false
                            }
                        ]
                    });
                });
            })(jQuery)
        </script>
    @endpush
@endsection
