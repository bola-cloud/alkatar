<!-- Js file  -->
<script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>
<!-- Use Bootstrap 5 bundle (includes Popper). Ensure version matches CSS used in head. -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('frontend/assets/js/plugins.js') }}"></script>
<script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>
<script src="{{ asset('frontend/assets/js/front/custom.js') }}"></script>
<script src="{{ asset('frontend/assets/js/front/extra.js') }}"></script>
<script src="{{ asset('frontend/assets/js/front/sweat_aleart.js') }}"></script>
<script src="{{ asset('frontend/assets/js/common.js') }}"></script>
{{-- toastr --}}
<script src="{{ asset('admin/js/toastr.min.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>


<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    @if (Session::has('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if (Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif
    @if (Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
@if (@$errors->any())
    <script>
        "use strict";
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    </script>
@endif
{{-- Diagnostic: log bootstrap script tags and detected version to console --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            var bsVersion = (window.bootstrap && window.bootstrap.Carousel && window.bootstrap.Carousel.VERSION) || (window.bootstrap && window.bootstrap.version) || 'unknown';
            console.info('[diag] Bootstrap version detected:', bsVersion);
            var bootstrapScriptTags = Array.from(document.querySelectorAll('script[src]')).filter(function(s){ return /bootstrap/i.test(s.src); });
            console.info('[diag] Bootstrap script tags on page:', bootstrapScriptTags.map(function(s){ return s.src; }));
            var indicators = document.querySelectorAll('[data-bs-slide-to]');
            console.info('[diag] Carousel indicator buttons count:', indicators.length);
        } catch (e) {
            console.info('[diag] Bootstrap diagnostic error', e);
        }
    });
</script> --}}
<script>
    // Fallback and diagnostics for carousel indicator clicks
    document.addEventListener('DOMContentLoaded', function () {
        try {
            document.addEventListener('click', function (ev) {
                var t = ev.target;
                // find nearest indicator button
                var btn = t.closest && t.closest('[data-bs-slide-to]');
                if (!btn) return;
                var idx = parseInt(btn.getAttribute('data-bs-slide-to'));
                // console.info('[diag] indicator clicked, idx=', idx, ' target=', btn);
                // Try to use Bootstrap's carousel instance if present
                try {
                    var carouselEl = document.querySelector(btn.getAttribute('data-bs-target') || btn.getAttribute('href'));
                    if (carouselEl) {
                        var inst = window.bootstrap && window.bootstrap.Carousel && window.bootstrap.Carousel.getOrCreateInstance
                            ? window.bootstrap.Carousel.getOrCreateInstance(carouselEl)
                            : null;
                        if (inst && typeof inst.to === 'function') {
                            inst.to(idx);
                            ev.preventDefault();
                            return;
                        }
                    }
                } catch (e) {
                    // console.warn('[diag] error forcing carousel to slide', e);
                }
            }, true);
        } catch (e) {
            // console.info('[diag] Carousel fallback attach error', e);
        }
    });
</script>
{{-- @if (env('APP_DEMO') == true) --}}
    {{-- for sandbox sslcommerz --}}
    {{-- <script>
        (function(window, document) {
            var loader = function() {
                var script = document.createElement("script"),
                    tag = document.getElementsByTagName("script")[0];
                script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(
                    7);
                tag.parentNode.insertBefore(script, tag);
            };

            window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
                loader);
        })(window, document);
    </script>
@else --}}
    {{-- for live sslcommerz --}}
    {{-- <script>
        (function(window, document) {
            var loader = function() {
                var script = document.createElement("script"),
                    tag = document.getElementsByTagName("script")[0];
                script.src = "https://seamless-epay.sslcommerz.com/embed.min.js?" + Math.random().toString(36)
                    .substring(7);
                tag.parentNode.insertBefore(script, tag);
            };

            window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
                loader);
        })(window, document);
    </script>
@endif --}}
