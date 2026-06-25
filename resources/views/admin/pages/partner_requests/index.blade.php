@extends('admin.master', ['menu' => 'crm', 'submenu' => 'partner_requests'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Partner Requests') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Partner Requests') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table">
                    <table class="dataTableHover row-border table-style table table-striped table-bordered text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Company Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ $req->id }}</td>
                                <td>{{ $req->name }}</td>
                                <td>{{ $req->company ?? 'N/A' }}</td>
                                <td>{{ $req->email }}</td>
                                <td>{{ $req->phone }}</td>
                                <td>
                                    <form action="{{ route('admin.partner-requests.update_status', $req->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm status-select" style="width: auto; min-width: 120px;">
                                            <option value="0" class="text-warning" {{ $req->status == 0 ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="1" class="text-success" {{ $req->status == 1 ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                                            <option value="2" class="text-danger" {{ $req->status == 2 ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="action__buttons">
                                        <a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#viewModal{{ $req->id }}" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.partner-requests.delete', $req->id) }}" class="btn-action delete text-danger" onclick="return confirm('{{ __('Are you sure you want to delete this request?') }}')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No Partner Requests Found!') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for details -->
    @foreach ($requests as $req)
        <div class="modal fade" id="viewModal{{ $req->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalTitle{{ $req->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="viewModalLongTitle">{{ __('Partner Request Details') }}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">{{ __('Name') }}:</h6>
                            <p class="text-muted fs-5">{{ $req->name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">{{ __('Company Name') }}:</h6>
                            <p class="text-muted fs-5">{{ $req->company ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">{{ __('Email') }}:</h6>
                            <p class="text-muted fs-5">{{ $req->email }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">{{ __('Phone') }}:</h6>
                            <p class="text-muted fs-5">{{ $req->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">{{ __('Why Join Us?') }}:</h6>
                            <p class="text-muted bg-light p-2 rounded" style="white-space: pre-line;">{{ $req->message }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
