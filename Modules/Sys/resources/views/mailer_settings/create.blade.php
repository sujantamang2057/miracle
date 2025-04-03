@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('mailer_setting_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.btn.create') }} {{ __('sys::models/mailer_settings.singular') }}
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::open(['route' => 'sys.mailerSettings.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::mailer_settings.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
