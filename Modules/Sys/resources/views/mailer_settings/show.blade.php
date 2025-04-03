@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('mailer_setting_detail', $mailerSetting) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/mailer_settings.symfony') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('sys::mailer_settings.show_fields')
                </div>
                <div class="d-flex column-gap-5 mt-3">
                    @if (checkSysPermissionList(['mailerSettings.edit', 'mailerSettings.update']))
                        {!! renderLinkButton(__('common::crud.btn.update'), route('sys.mailerSettings.edit', [$mailerSetting->setting_id]), 'edit', 'primary', '') !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
