@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('resource_detail', $resource) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/resources.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::resources.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.resources.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['resources.create', 'resources.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.resources.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['resources.edit', 'resources.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.resources.edit', [$resource->resource_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('resources.destroy'))
                    {!! renderDeleteBtn('cmsadmin.resources.destroy', $resource->resource_id, $resource->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
