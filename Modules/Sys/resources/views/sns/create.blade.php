@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('sns_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.create') }} {{ __('sys::models/sns.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'sys.sns.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::sns.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
