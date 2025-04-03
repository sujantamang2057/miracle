@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('page') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('cmsadmin::models/pages.plural') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('cmsadmin::pages.table')
        </div>
    </div>
    @include('common::__partial.image_edit')
@endsection

@include('common::__partial.reinit_index_script')
@include('common::__partial.swal_datatable')
@include('common::__partial.regenerate')
@include('common::__partial.publish_toggle')
@include('common::__partial.bulk_actions')
@include('common::__partial.datatables-rowreorder')
@include('common::__partial.datatables-column-filter')
