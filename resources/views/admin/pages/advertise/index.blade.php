@extends('admin.master', ['menu' => 'advertise'])
@section('title', isset($title) ? $title : '')
@push('post_styles')
    <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div id="table-url" data-url="{{ route('admin.advertise') }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Advertise')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Advertise')}}</li>
                        </ul>
                    </nav>
                    <div class="ms-3">
                        <a href="{{ route('admin.advertise.create') }}" class="btn btn-primary">{{ __('Add Advertise') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table">
                    <table id="AdvertiseTable" class="row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Image')}}</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{ __('Subtitle')}}</th>
                            <th>{{ __('Location')}}</th>
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
        <script src="{{asset('backend/js/admin/datatables/advertise.js')}}"></script>
        <!-- Page level custom scripts -->
        <script>
            (function($){
                'use strict';
                $(document).on('click', '.btn-action.delete, a.delete', function(e){
                    if(!confirm('{{ __('Are you sure?') }}')){
                        e.preventDefault();
                        return false;
                    }
                });
            })(jQuery);
        </script>
    @endpush
@endsection
