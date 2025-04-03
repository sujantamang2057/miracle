@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('emailTemplate') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/email_templates.plural') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            @include('sys::email_templates.table')
        </div>
    </div>
@endsection

@include('common::__partial.swal_datatable')
@include('common::__partial.reinit_index_script')
@include('common::__partial.regenerate')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
@include('common::__partial.bulk_actions')
@include('common::__partial.datatables-column-filter')
