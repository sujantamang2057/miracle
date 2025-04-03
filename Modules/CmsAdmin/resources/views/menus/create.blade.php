@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('menu_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.create') }} {{ __('cmsadmin::models/menus.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::open(['route' => 'cmsadmin.menus.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::menus.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
