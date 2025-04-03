@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('banner_detail', $banner) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/banners.singular') }} {{ __('common::crud.detail') }} </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::banners.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.banners.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['banners.create', 'banners.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.banners.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['banners.edit', 'banners.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.banners.edit', [$banner->banner_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('banners.destroy'))
                    {!! renderDeleteBtn('cmsadmin.banners.destroy', $banner->banner_id, $banner->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
@include('common::__partial.remove_image_js')
