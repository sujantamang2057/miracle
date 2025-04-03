<!DOCTYPE html>
<html lang= "{{ getAppLocaleEx() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" href="{{ asset('img/site-icon/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/site-icon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/site-icon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/site-icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/site-icon/favicon-16x16.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Noto+Sans+JP:wght@100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('theme/infyom/css/backend.css') }}">
    @stack('third_party_stylesheets')
    @stack('page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed {{ getBackendBodyClass() }}">
    <div class="wrapper">
        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown lang-menu">
                    <div class="float-right">
                        @include('layouts.locale')
                    </div>
                </li>
                @include('layouts._header_shortcuts')
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        @php
                            $profileImage = auth()->user()->profile_image;
                            $profileImagePath = !empty($profileImage)
                                ? STORAGE_DIR_NAME . DS . USER_FILE_DIR_NAME . DS . IMAGE_WIDTH_200 . DS . $profileImage
                                : '';
                            $profileImageUrl =
                                !empty($profileImagePath) && file_exists($profileImagePath) ? asset($profileImagePath) : asset(DEFAULT_PROFILE_IMAGE);
                        @endphp
                        <img src="{{ $profileImageUrl }}" class="user-image" alt="{{ __('sys::models/users.fields.profile_image') }}">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{ $profileImageUrl }}" class="" alt="{{ __('sys::models/users.fields.profile_image') }}">
                            <p>
                                {{ Auth::user()->name }}
                                <small>{{ __('sys::models/users.text.member_since') }} {{ Auth::user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="{{ route('sys.profile.index') }}"
                                class="btn btn-default btn-flat">{{ __('sys::models/users.btn.profile') }}</a>
                            <a href="javascript:void(0);" class="btn btn-default btn-flat float-right" id="logOutBtn">
                                {{ __('sys::models/users.btn.sign_out') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('theme/infyom/js/backend.js') }}"></script>
    @stack('third_party_scripts')
    @stack('page_scripts')
    <script>
        $(function() {
            $('body').on('click', '#logOutBtn', function(e) {
                event.preventDefault();
                window.swal.fire({
                    title: "{{ config('app.name') }}",
                    text: "{{ __('common::backend.messages.logout_confirm_msg') }}",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::backend.btn.logout_yes') }}",
                    cancelButtonText: "{{ __('common::backend.btn.logout_cancel') }}"
                }).then((result) => {
                    if (result.value) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
