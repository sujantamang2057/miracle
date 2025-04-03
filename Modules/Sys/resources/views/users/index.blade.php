@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/users.plural') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            @include('sys::users.table')
        </div>
    </div>
    @include('common::__partial.image_edit')
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.reinit_index_script')
@include('common::__partial.active_toggle')
@include('common::__partial.datatables-column-filter')
