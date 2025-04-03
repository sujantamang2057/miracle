@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('site_setting_detail', $siteSetting) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/site_settings.singular') }} {{ __('common::crud.text.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('sys::site_settings.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkSysPermissionList(['siteSettings.edit', 'siteSettings.update']))
                    {!! renderLinkButton(__('common::crud.btn.update'), route('sys.siteSettings.edit', [$siteSetting->setting_id]), 'edit', 'primary', '') !!}
                @endif
            </div>
        </div>
    </div>
@endsection
