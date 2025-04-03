@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_trash_detail', $news) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/news.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::news.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('news.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.news.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['news.create', 'news.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.news.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('news.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $news->news_id,
                        route('cmsadmin.news.restore', ['id' => $news->news_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('news.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.news.permanentDestroy', $news->news_id, true, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
