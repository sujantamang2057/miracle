@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('seo_trash_detail', $seo) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/seos.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::seos.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('seos.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.seos.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['seos.create', 'seos.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.seos.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('seos.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $seo->id,
                        route('cmsadmin.seos.restore', ['id' => $seo->id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('seos.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.seos.permanentDestroy', $seo->id, true, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
