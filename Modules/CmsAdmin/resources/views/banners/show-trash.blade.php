@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('banner_trash_detail', $banner) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/banners.singular') }} {{ __('common::crud.detail') }} -
                        {{ __('common::crud.trashed') }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card card-width-lg">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::banners.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('banners.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.banners.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['banners.create', 'banners.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.banners.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('banners.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $banner->banner_id,
                        route('cmsadmin.banners.restore', ['id' => $banner->banner_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('banners.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.banners.permanentDestroy', $banner->banner_id, $banner->reserved == 2, null, 'trash', 'permanent') !!}
                @endif

            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
