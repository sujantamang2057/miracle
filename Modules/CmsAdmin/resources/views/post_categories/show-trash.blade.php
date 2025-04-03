@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_category_trash_detail', $postCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/post_categories.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::post_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('postCategories.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.postCategories.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['postCategories.create', 'postCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.postCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('postCategories.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $postCategory->category_id,
                        route('cmsadmin.postCategories.restore', ['id' => $postCategory->category_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('postCategories.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'cmsadmin.postCategories.permanentDestroy',
                        $postCategory->category_id,
                        $postCategory->reserved == 2,
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
