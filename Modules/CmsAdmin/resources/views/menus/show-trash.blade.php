@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('menu_trash_detail', $menu) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/menus.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::menus.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('menus.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.menus.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['menus.create', 'menus.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.menus.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('menus.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $menu->menu_id,
                        route('cmsadmin.menus.restore', ['id' => $menu->menu_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('menus.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.menus.permanentDestroy', $menu->menu_id, $menu->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
