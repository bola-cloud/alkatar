<!-- jQuery -->
<script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/common.js') }}"></script>

<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('admin/css/toastr.min.css') }}">
<script src="{{ asset('admin/js/toastr.min.js') }}"></script>

<!-- Custom JS Hooks -->
<script>
    function addToCart(id) {
        // Logic for AJAX add to cart
        toastr.success('Product added to cart!');
    }

    function addToWishlist(id) {
        // Logic for AJAX add to wishlist
        toastr.info('Product added to wishlist!');
    }
</script>

<script src="{{ asset('js/app.js') }}"></script>
