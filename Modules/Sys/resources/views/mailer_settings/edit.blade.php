@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('mailer_setting_edit', $mailerSetting) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.btn.edit') }} {{ __('sys::models/mailer_settings.singular') }}
                        {{ __('sys::models/mailer_settings.symfony') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::model($mailerSetting, [
                'route' => ['sys.mailerSettings.update', $mailerSetting->setting_id],
                'method' => 'patch',
            ]) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::mailer_settings.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
