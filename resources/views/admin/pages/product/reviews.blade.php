@extends('admin.master', ['menu' => 'products', 'submenu' => 'product'])
@section('title', 'Product Reviews')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Product Reviews')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Product Reviews')}}</li>
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
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Product')}}</th>
                            <th>{{__('Reviewer')}}</th>
                            <th>{{__('Rating')}}</th>
                            <th>{{__('Feedback')}}</th>
                            <th>{{__('Visible')}}</th>
                            <th>{{__('Created')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->product->en_Product_Name ?? $review->product->fr_Product_Name ?? '-' }}</td>
                                <td>{{ $review->user->name ?? 'Guest' }}</td>
                                <td>{{ $review->rating }}</td>
                                <td style="max-width:360px">{{ Str::limit($review->feedback, 120) }}</td>
                                <td>
                                    @if($review->is_visible)
                                        <span class="badge bg-success">{{__('Visible')}}</span>
                                    @else
                                        <span class="badge bg-secondary">{{__('Hidden')}}</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.product.review.toggle', $review->id) }}" class="btn btn-sm btn-warning">{{ $review->is_visible ? __('Hide') : __('Show') }}</a>
                                    <a href="{{ route('admin.product.review.delete', $review->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('{{__('Are you sure?')}}')">{{__('Delete')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">{{ $reviews->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection
