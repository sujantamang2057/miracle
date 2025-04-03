@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('site_setting_edit', $siteSetting) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.btn.edit') }} {{ __('sys::models/site_settings.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-6">
            {!! Form::model($siteSetting, [
                'route' => ['sys.siteSettings.update', $siteSetting->setting_id],
                'method' => 'patch',
            ]) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::site_settings.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! renderSubmitButton(__('common::crud.btn.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.btn.cancel'), route('sys.siteSettings.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
