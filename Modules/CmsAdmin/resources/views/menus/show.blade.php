@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('menu_detail', $menu) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/menus.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::menus.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.menus.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['menus.create', 'menus.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.menus.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['menus.edit', 'menus.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.menus.edit', [$menu->menu_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('menus.destroy'))
                    {!! renderDeleteBtn('cmsadmin.menus.destroy', $menu->menu_id, $menu->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.active_toggle')
@include('common::__partial.reserved_toggle')
