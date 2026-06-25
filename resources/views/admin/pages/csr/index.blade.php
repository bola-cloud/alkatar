@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'csr'])
@section('title', isset($title) ? $title : '')
@section('content')

    <div id="table-url" data-url="{{ route('admin.csr.index') }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('CSR Initiatives & Projects')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('CSR Initiatives')}}</li>
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
                        <a href="{{route('admin.csr.create')}}" class="btn btn-md btn-info">{{ __('Add CSR Initiative')}}</a>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="CsrTable" class="row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Image')}}</th>
                            <th>{{ __('Title EN')}}</th>
                            <th>{{ __('Title AR')}}</th>
                            <th>{{ __('Type')}}</th>
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
                    $('#CsrTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: $('#table-url').data("url"),
                        columns: [
                            {
                                data: 'image',
                                name: 'image',
                                orderable: false
                            },
                            {
                                data: 'title_en',
                                name: 'title_en'
                            },
                            {
                                data: 'title_ar',
                                name: 'title_ar'
                            },
                            {
                                data: 'type',
                                name: 'type'
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
