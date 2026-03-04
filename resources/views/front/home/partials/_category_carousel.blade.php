@php
    // $categories: collection of category models
    $carouselId = $carouselId ?? 'categoryCarousel_' . substr(md5(uniqid('', true)), 0, 6);
@endphp

<div class="product-carousel-section category-carousel-section position-relative">
    <button class="carousel-arrow left-arrow btn btn-white shadow-sm position-absolute"
        aria-label="{{ __('Previous') }}" data-target="#{{ $carouselId }}">
        <i class="bi bi-chevron-left fs-4"></i>
    </button>

    <div id="{{ $carouselId }}"
        class="product-carousel-wrapper category-carousel-wrapper d-flex gap-2 overflow-auto pt-2 pb-3">
        @foreach($categories as $category)
            @php
                $name = langConverter($category->en_Category_Name, $category->fr_Category_Name);
                $img = $category->Category_Icon ?? null;
                $imgUrl = asset('new-design/images/special-offer.png'); // Fallback
                if ($img) {
                    $imgUrl = asset(CategoryImage() . $img);
                }
              @endphp

            <div class="product-item category-item card h-100 d-flex flex-column">
                <div class="position-relative">
                    <a href="{{ route('categories.show', ['slug' => $category->en_Category_Slug]) }}">
                        <img src="{{ $imgUrl }}" loading="lazy" class="card-img-top p-3" alt="{{ $name }}"
                            style="height:160px; object-fit:contain;"
                            onerror="this.onerror=null;this.src='{{ asset('new-design/images/special-offer.png') }}';">
                    </a>
                </div>

                <div class="card-body d-flex flex-column flex-grow-1 text-center">
                    <h6 class="card-title mb-2">
                        <a href="{{ route('categories.show', ['slug' => $category->en_Category_Slug]) }}"
                            class="text-inherit text-decoration-none">
                            {{ \Illuminate\Support\Str::limit($name, 40) }}
                        </a>
                    </h6>

                    <div class="product-unit mb-2">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-tag me-1"></i>{{ __('Category') }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <span class="text-warning">★★★★★</span>
                    </div>

                    <div class="mt-auto">
                        <div class="w-100">
                            <a href="{{ route('categories.show', ['slug' => $category->en_Category_Slug]) }}"
                                class="add-cart btn btn-success w-100">
                                <i class="bi bi-bag me-1"></i> {{ __('View Products') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button class="carousel-arrow right-arrow btn btn-white shadow-sm position-absolute" aria-label="{{ __('Next') }}"
        data-target="#{{ $carouselId }}">
        <i class="bi bi-chevron-right fs-4"></i>
    </button>

    <style>
        .category-carousel-section {
            padding: 10px 40px;
        }

        #{{ $carouselId }} {
            scroll-behavior: smooth;
        }

        .category-carousel-wrapper::-webkit-scrollbar {
            display: none;
        }

        .category-carousel-wrapper {
            -ms-overflow-style: none;
            scrollbar-width: none;
            gap: 1rem;
        }

        .category-carousel-section .carousel-arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        .category-item {
            flex: 0 0 calc((100% - 3rem) / 4);
            max-width: calc((100% - 3rem) / 4);
            transition: transform 0.3s ease;
        }

        .category-item:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 1199px) {
            .category-item {
                flex: 0 0 calc((100% - 2rem) / 3);
                max-width: calc((100% - 2rem) / 3);
            }
        }

        @media (max-width: 767px) {
            .category-item {
                flex: 0 0 calc(50% - 10px);
                max-width: calc(50% - 10px);
            }
        }

        @media (max-width: 479px) {
            .category-item {
                flex: 0 0 calc(100% - 10px);
                max-width: calc(100% - 10px);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const section = document.querySelector('#{{ $carouselId }}');
            if (!section) return;

            const leftBtn = section.parentElement.querySelector('.left-arrow');
            const rightBtn = section.parentElement.querySelector('.right-arrow');

            const scrollByAmount = () => {
                const card = section.querySelector('.category-item');
                if (!card) return 260;
                const style = window.getComputedStyle(section);
                const gap = parseInt(style.gap || 16, 10) || 16;
                return card.offsetWidth + gap;
            };

            leftBtn && leftBtn.addEventListener('click', () => {
                section.scrollBy({ left: -scrollByAmount(), behavior: 'smooth' });
            });

            rightBtn && rightBtn.addEventListener('click', () => {
                section.scrollBy({ left: scrollByAmount(), behavior: 'smooth' });
            });
        });
    </script>
</div>