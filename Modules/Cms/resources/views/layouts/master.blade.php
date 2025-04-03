<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $seo->description ?? $siteSetting->meta_description ?? $page->meta_description }}">
    <meta name="keywords" content="{{ $seo->keyword ?? $siteSetting->meta_key ?? $page->meta_keyword }}">
    <meta name="author" content="{{ $author ?? '' }}">
    <title>@yield('title', $seo->title ?? ($siteSetting->site_name ?? $page->page_title))</title>
    <link rel="icon" href="{{ asset('img/site-icon/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/site-icon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/site-icon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/site-icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/site-icon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/site-icon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('img/site-icon/safari-pinned-tab.svg" color="#5bbad5') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!--google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!--css-->
    <link rel="stylesheet" href="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/bootstrap/bootstrap-icons.min.css') }}" />
    @stack('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset(CMS_THEME_ASSETS_DIR_PATH . '/css/app.css') }}">
    @stack('page_css')
</head>

<body class="{{ getFrontendBodyClass() }}">
    <x-blocks.header />

    <div class="spacer w-100"></div>

    @yield('content_header')

    @yield('content')

    <x-blocks.footer />

    <script src="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset(CMS_THEME_ASSETS_DIR_PATH . '/js/app.js') }}"></script>
    @stack('third_party_scripts')
    @stack('page_scripts')
</body>

</html>
