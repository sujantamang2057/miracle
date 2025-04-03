@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('site_setting_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.btn.create') }} {{ __('sys::models/site_settings.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::open(['route' => 'sys.siteSettings.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::site_settings.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.btn.cancel'), route('sys.siteSettings.index'), 'times', 'warning', '') !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
