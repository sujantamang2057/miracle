@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_multidata_details', $blog) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blogs.blog_multidata.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            @include('cmsadmin::blog_details.table')
        </div>
    </div>
@endsection

@include('common::__partial.reinit_index_script')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.bulk_actions')
@include('common::__partial.datatables-column-filter')
@include('common::__partial.datatables-rowreorder')
