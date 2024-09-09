<div>
    <div class="mobile-header-area d-block d-lg-none">
        <div class="container">
            <div class="menu-wrap">
                <div class="header-left">
                    <a class="brand-logo" href="{{ route('front') }}"><img class="brand-image"
                            src="{{ asset(IMG_LOGO_PATH . $allsettings['main_logo']) }}"
                            alt="{{ __('zairito') }}" /></a>
                </div>

                <div class="header-right">
                    <a role="button" class="cart-btn header-btn" href="{{ route('cart.content') }}">
                        <div class="btn-left">
                            <i class="btn-icon flaticon-shopping-bag"></i>
                            <span class="count totalCountItem">{{ cartCountItem() }}</span>
                        </div>
                    </a>


                    <button class="menu-bar" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasMobileMenu" aria-controls="offcanvasMobileMenu"><i
                            class="fas fa-bars"></i></button>
                </div>
            </div>


            <div class="search-area mt-3 !w-full !max-w-full mx-auto">
                <form action="{{ route('category.product') }}" method="get">
                    <div class="search-wrap">
                        <input type="text" class="form-control" id="mobile-search" name="search"
                            placeholder="{{ __('Search Here') }}" style="border-radius: 0 !important;" />
                        <button type="submit" class="search-btn"><i class="flaticon-search"></i></button>
                    </div>
                </form>
                <div id="mobile-search-suggestions" class="absolute bg-white shadow-md  w-3/4  z-10"></div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('mobile-search');
        const suggestionsContainer = document.getElementById('mobile-search-suggestions');
        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value.trim();
                if (query.length > 1) {
                    fetchSuggestions(query);
                } else {
                    suggestionsContainer.innerHTML = '';
                }
            }, 300);
        });

        function fetchSuggestions(query) {
            fetch(`/search/suggest?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySuggestions(data, query);
                })
                .catch(error => console.error('Error:', error));
        }

        function isArabic(text) {
            const arabicPattern = /[\u0600-\u06FF]/;
            return arabicPattern.test(text);
        }


        function displaySuggestions(suggestions, query) {
            suggestionsContainer.innerHTML = '';
            if (suggestions.length > 0) {
                const ul = document.createElement('ul');
                ul.className = 'list-none p-0 m-0';
                const isArabicQuery = isArabic(query);

                suggestions.forEach(suggestion => {
                    const li = document.createElement('li');
                    li.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                    const displayName = isArabicQuery ? suggestion.fr_Product_Name : suggestion.en_Product_Name;

                    li.textContent = displayName;

                    li.onclick = () => {
                        location.href = `/product/single/${suggestion.en_Product_Slug}`;
                        // searchInput.value = displayName;
                        // suggestionsContainer.innerHTML = '';
                    };
                    ul.appendChild(li);
                });
                suggestionsContainer.appendChild(ul);
            }
        }

        // Close suggestions when clicking outside
        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
                suggestionsContainer.innerHTML = '';
            }
        });
    });
</script>