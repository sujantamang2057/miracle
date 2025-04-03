@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('newsCategory_trash_detail', $newsCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/news_categories.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::news_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('newsCategories.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.newsCategories.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['newsCategories.create', 'newsCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.newsCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('newsCategories.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $newsCategory->category_id,
                        route('cmsadmin.newsCategories.restore', ['id' => $newsCategory->category_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('newsCategories.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'cmsadmin.newsCategories.permanentDestroy',
                        $newsCategory->category_id,
                        $newsCategory->reserved == 2,
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
