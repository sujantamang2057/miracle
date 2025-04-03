@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_category_trash_detail', $blogCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blog_categories.singular') }} {{ __('common::crud.detail') }} -
                        {{ __('common::crud.trashed') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card card-width-lg">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blog_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('blogCategories.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blogCategories.trashList'), 'chevron-circle-left', 'warning', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blogCategories.create', 'blogCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blogCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('blogCategories.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $blogCategory->cat_id,
                        route('cmsadmin.blogCategories.restore', ['id' => $blogCategory->cat_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('blogCategories.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'cmsadmin.blogCategories.permanentDestroy',
                        $blogCategory->cat_id,
                        $blogCategory->reserved == 2,
                        null,
                        'trash',
                        'permanent',
                    ) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
