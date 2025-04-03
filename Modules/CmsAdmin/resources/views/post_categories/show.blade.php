@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_category_detail', $postCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/post_categories.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::post_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.postCategories.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['postCategories.create', 'postCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.postCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['postCategories.edit', 'postCategories.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.postCategories.edit', [$postCategory->category_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('postCategories.destroy'))
                    {!! renderDeleteBtn('cmsadmin.postCategories.destroy', $postCategory->category_id, $postCategory->reserved == 2) !!}
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
