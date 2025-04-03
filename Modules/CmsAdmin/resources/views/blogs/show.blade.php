@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_detail', $blog) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blogs.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blogs.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blogs.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['blogs.create', 'blogs.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blogs.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blogs.edit', 'blogs.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.blogs.edit', [$blog->blog_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('blogs.multidata'))
                    {!! renderLinkButton(__('common::multidata.name'), route('cmsadmin.blogs.multidata', [$blog->blog_id]), 'clone', 'secondary', '') !!}
                @endif
                @if (checkCmsAdminPermission('blogs.destroy'))
                    {!! renderDeleteBtn('cmsadmin.blogs.destroy', $blog->blog_id, true) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.remove_image_js')
