@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : __('Wishlist'))
@section('content')

	@include('front.partials.category_banner', ['title' => __('Wishlist')])

	<div class="container py-5">
		<div class="row">
			<div class="col-12 mb-4">
				<a href="{{ url()->previous() }}" class="d-inline-flex align-items-center text-decoration-none text-dark mb-3">
					<i class="bi bi-arrow-left me-2"></i>
					{{ __('Back to menu') }}
				</a>
				<h1 class="h3">{{ __('Wishlist') }}</h1>
			</div>
		</div>

		<div class="row gy-4">
			<div class="col-lg-8">
				<div class="d-flex flex-column gap-4">
					@forelse($wishlists as $wishlist)
						@php $product = $wishlist->product; @endphp
						<div class="card shadow-sm border-0">
							<div class="card-body d-flex align-items-center cart-card">
								<div class="me-4">
									<a href="{{ route('single.product', $product->en_Product_Slug ?? '') }}">
										<img src="{{ asset(ProductImage() . $product->Primary_Image) }}" alt="{{ $product->en_Product_Name }}" style="width:96px;height:96px;object-fit:cover;" class="rounded">
									</a>
								</div>

								<div class="flex-grow-1">
									<h3 class="h6 mb-1"><a href="{{ route('single.product', $product->en_Product_Slug ?? '') }}" class="text-dark">{{ langConverter($product->en_Product_Name ?? $product->name, $product->fr_Product_Name ?? '') }}</a></h3>
									{!! productReview($product->id) !!}

									@if($product->ItemTag)
										<div class="small text-muted mb-1">{{ $product->ItemTag }}</div>
									@endif

									<div class="small mt-2">
										<input type="hidden" name="quantity" value="1" id="product_quantity">
										@if($product->colors && $product->colors->count())
											<div class="product-variable-color">
												@foreach($product->colors as $color)
													<label class="me-1">
														<input type="hidden" name="colorId" value="{{ $color->id }}">
														<input name="productColor" class="color-select" type="radio" value="{{ $color->id }}">
														<span style="display:inline-block;width:14px;height:14px;background:{{ $color->ColorCode }};border-radius:3px;"></span>
													</label>
												@endforeach
											</div>
										@endif
									</div>
								</div>

								<div class="text-end ms-3" style="min-width:160px;">
									<div class="mb-3">
										<button class="btn btn-sm btn-outline-danger deleteWishlist" data-id="{{ $wishlist->id }}" title="{{ __('Delete Item') }}"><i class="bi bi-x-lg"></i></button>
									</div>

									<div class="fw-bold">{{ currencyConverter($product->Discount_Price) }}</div>
									<div class="text-muted small">{{ __('Price') }}</div>

									<div class="mt-3">
										<a href="javascript:void(0)" data-id="{{ $product->id }}" class="btn btn-sm btn-success addCart">{{ __('Add To Cart') }}</a>
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="card">
							<div class="card-body text-center py-5">
								<h5>{{ __('Your wishlist is empty') }}</h5>
								<p class="text-muted">{{ __('Your wishlist is empty. Please go to your home page for listing it.') }}</p>
							</div>
						</div>
					@endforelse
				</div>
			</div>

			<div class="col-lg-4">
				<div class="sticky-top" style="top:100px;">
					<div class="p-4" style="border:2px solid #d8eec3;border-radius:10px;background:#fff;">
						<h5 class="card-title" style="color:#ff9a00;font-weight:700;margin-bottom:8px;">{{ __('Wishlist Summary') }}</h5>
						<hr style="border-top:1px solid #e6e6e6;margin:8px 0 16px 0;">

						<div class="d-flex justify-content-between mb-2">
							<div class="text-muted">{{ __('Items:') }}</div>
							<div class="fw-bold">{{ $wishlists->count() }}</div>
						</div>

						<div style="height:12px"></div>
						<a href="{{ route('cart.content') }}" class="btn w-100" style="background:#b6bf21;color:#ffffff;border-radius:28px;padding:12px 20px;font-weight:600;">{{ __('View Cart') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="deleteWishListUrl" data-url="{{ route('wishlist.delete') }}"></div>
	<div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>

@endsection

@push('scripts')
	<script src="{{ asset('frontend/assets/js/pages/wishlist.js') }}"></script>
@endpush
