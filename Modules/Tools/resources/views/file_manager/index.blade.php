@extends('layouts.app')

@push('page_css')
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/theme/tools/css/filemanager.css') }}">

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="{{ asset($dir . '/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir . '/css/theme.css') }}">
@endpush

@section('content')
    {{ Breadcrumbs::render('file_manager') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('tools::common.file_manager') }}</h4>
            </div>
            <div id="elfinder" class="full_height"></div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('/theme/tools/js/filemanager.js') }}"></script>

    <!-- elFinder JS (REQUIRED) -->
    <script src="{{ asset($dir . '/js/elfinder.min.js') }}"></script>

    @if ($locale)
        <!-- elFinder translation (OPTIONAL) -->
        <script src="{{ asset($dir . "/js/i18n/elfinder.$locale.js") }}"></script>
    @endif
@endpush

@include('tools::__partial.elfinder_scripts')
