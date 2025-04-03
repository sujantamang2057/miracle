@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('page_trash_detail', $page) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/pages.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::pages.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('pages.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.pages.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['pages.create', 'pages.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.pages.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('pages.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $page->page_id,
                        route('cmsadmin.pages.restore', ['id' => $page->page_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('pages.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.pages.permanentDestroy', $page->page_id, $page->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
