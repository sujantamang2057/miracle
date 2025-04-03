@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('resource_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.create') }} {{ __('cmsadmin::models/resources.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'cmsadmin.resources.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::resources.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
