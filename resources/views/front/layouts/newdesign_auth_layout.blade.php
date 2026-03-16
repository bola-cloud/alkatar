<!doctype html>
<html lang="{{ session('HTML_LANG', app()->getLocale() ?? 'en') }}" dir="{{ session('lang_dir', 'ltr') }}">

@section('head')
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <title>@yield('title', 'Auth') | {{ $allsettings['app_title'] ?? config('app.name', 'HiSpeed') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('new-design/css/register.css') }}" />
  <style>
    .auth-lang-switcher {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 1000;
    }
    [dir="rtl"] .auth-lang-switcher {
      right: auto;
      left: 20px;
    }
    .auth-lang-switcher .btn {
      background: white;
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 20px;
      padding: 5px 15px;
      font-weight: 600;
    }
  </style>
  @stack('head')
</head>
@show

<body>
  <div class="auth-lang-switcher">
    <div class="dropdown">
      @php
        $availableLangs = languageList() instanceof \Illuminate\Support\Collection ? languageList()->unique('locale')->values() : collect(languageList())->unique('locale')->values();
        $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
        $currentLocale = session('APP_LOCALE', app()->getLocale() ?? 'en');
      @endphp
      <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-globe me-1"></i>
        {{ getLanguage($currentLocale)->name ?? strtoupper($currentLocale) }}
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        @foreach($availableLangs as $langItem)
          @if($langItem->status == 1)
            <li>
              <a class="dropdown-item {{ $currentLocale == $langItem->locale ? 'active' : '' }}"
                href="{{ route('locale.switch', $langItem->locale) }}">{{ $langItem->name }}</a>
            </li>
          @endif
        @endforeach
      </ul>
    </div>
  </div>

  @yield('content')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>

</html>
