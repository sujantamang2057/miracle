@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('contact') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/contacts.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            @include('cmsadmin::contacts.table')
        </div>
    </div>
    @include('cmsadmin::contacts.modal')
@endsection
@include('common::__partial.datatables-column-filter')
