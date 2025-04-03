@php
    $siteName = getSiteSettings('site_name');
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" target = "_blank" class="brand-link" title = "{{ __('common::backend.text.site_preview') }}">
        <img src="{{ asset('img/logo.png') }}" alt="Site Logo" class="brand-image">
        <span class="brand-text font-weight-light">{{ $siteName ?: config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
