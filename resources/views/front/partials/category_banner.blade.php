@php $bannerImage = asset('new-design/images/banner.png'); @endphp
<style>
    .category-banner .banner-bg {
        background-image: url('{{ $bannerImage }}');
        background-size: cover;
        background-position: center;
        height: 160px;
    }
</style>

@php
    // Use Laravel locale as source of truth for breadcrumb translations
    $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
    $isDisplayAr = in_array($displayLocale, ['ar', 'fr']);
    $htmlLang = $displayLocale;
@endphp

<div class="category-banner">
    <div class="banner-inner">
        <div class="banner-bg"></div>
        <div class="banner-overlay"></div>
        <div class="banner-center">
            <div class="breadcrumb-row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb"
                        style="background:transparent;padding:0;margin:0;display:inline-flex;align-items:center;gap:6px">
                        <li class="breadcrumb-item">
                            <a href="{{ route('front') }}"
                                style="color:rgba(255,255,255,0.9);display:inline-flex;align-items:center;gap:6px;">
                                <i class="bi bi-house" aria-hidden="true" style="font-size:14px;color:inherit"></i>
                                <span class="visually-hidden">{{ __('Home', [], $htmlLang) }}</span>
                            </a>
                        </li>
                        <li class="crumb">&nbsp;›&nbsp; <span
                                style="color:#d0e8d0">{{ __('Category', [], $htmlLang) }}</span></li>
                        @php
                            // Determine display name: if a Category model was passed use its localized column,
                            // otherwise use the provided $title (which may already be translated).
                            $displayTitle = $title ?? __('Category', [], $htmlLang);
                            if (isset($category) && $category) {
                                if ($isDisplayAr) {
                                    $displayTitle = $category->fr_Category_Name ?? $category->en_Category_Name ?? $displayTitle;
                                } else {
                                    $displayTitle = $category->en_Category_Name ?? $category->fr_Category_Name ?? $displayTitle;
                                }
                            }
                        @endphp
                        <li class="crumb">&nbsp;›&nbsp; <span style="color:#fff">{{ $displayTitle }}</span></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>