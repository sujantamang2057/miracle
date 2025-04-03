<!DOCTYPE html>
<html lang="{{ getAppLocaleEx() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ asset('img/site-icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset(BACKEND_URL_CSS) }}">
    @stack('third_party_stylesheets')
    @stack('page_css')

</head>

@yield('content')

<script src="{{ asset(BACKEND_URL_JS) }}"></script>
@stack('third_party_scripts')
@stack('page_scripts')
</body>

</html>
