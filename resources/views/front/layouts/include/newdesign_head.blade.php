<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hi speed')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- New design CSS (place new-design folder under public/) -->
    <link rel="stylesheet" href="{{ asset('new-design/css/style.css') }}">

    {{-- Dynamic favicon from main logo setting (falls back to a default favicon) --}}
    @php
      $faviconPath = null;
      if (isset($allsettings) && !empty($allsettings['main_logo'])) {
        $faviconPath = asset(IMG_LOGO_PATH . $allsettings['main_logo']);
      } elseif (isset($allsettings) && !empty($allsettings['footer_logo'])) {
        $faviconPath = asset(IMG_LOGO_PATH . $allsettings['footer_logo']);
      } else {
        $faviconPath = asset('new-design/favicon.ico');
      }
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconPath }}">
    <link rel="apple-touch-icon" href="{{ $faviconPath }}">

    @if(app()->getLocale() == 'ar')
    <style>
        html[dir="rtl"], body[dir="rtl"], [dir="rtl"] { direction: rtl !important; }
        [dir="rtl"] .text-start { text-align: right !important; }
        [dir="rtl"] .text-end { text-align: left !important; }
        [dir="rtl"] .float-start { float: right !important; }
        [dir="rtl"] .float-end { float: left !important; }
        [dir="rtl"] .ms-auto { margin-inline-start: auto !important; }
        [dir="rtl"] .me-auto { margin-inline-end: auto !important; }
        /* Ensure pill/button alignments and nav order work better in RTL */
        [dir="rtl"] .category-pills { direction: rtl; }

    </style>
    @endif
</head>

<style>
  /* Modal cosmetic adjustments to match new design */
  .modal-content {
    border-radius: 18px;
    overflow: hidden;
  }
  .modal .modal-header { border-bottom: none; }
  #sizeModal .modal-body { padding: 1.75rem; }
  #sizeModal .size-option, #sizeModal .weight-option {
    min-width: 120px;
    margin: 6px;
  }
  #sizeModal .size-option.selected, #sizeModal .weight-option.selected {
    background-color: #9fc23a;
    color: #fff;
    border-color: #9fc23a;
  }
</style>
