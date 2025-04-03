@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('seo_detail', $seo) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/seos.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::seos.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.seos.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['seos.create', 'seos.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.seos.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['seos.edit', 'seos.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.seos.edit', [$seo->id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('seos.destroy'))
                    {!! renderDeleteBtn('cmsadmin.seos.destroy', $seo->id) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
