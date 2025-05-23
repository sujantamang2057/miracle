@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_category_detail', $blogCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blog_categories.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blog_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blogCategories.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['blogCategories.create', 'blogCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blogCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blogCategories.edit', 'blogCategories.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.blogCategories.edit', [$blogCategory->cat_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('blogCategories.destroy'))
                    {!! renderDeleteBtn('cmsadmin.blogCategories.destroy', $blogCategory->cat_id, $blogCategory->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
@include('common::__partial.remove_image_js')
