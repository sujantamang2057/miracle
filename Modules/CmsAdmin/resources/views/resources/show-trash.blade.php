@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('resource_detail', $resource) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/resources.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::resources.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('resources.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.resources.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['resources.create', 'resources.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.resources.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('resources.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $resource->resource_id,
                        route('cmsadmin.resources.restore', ['id' => $resource->resource_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('resources.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.resources.permanentDestroy', $resource->resource_id, $resource->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
