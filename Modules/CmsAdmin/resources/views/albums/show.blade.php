@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('album_detail', $album) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/albums.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::albums.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.albums.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['albums.create', 'albums.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.albums.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['albums.edit', 'albums.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.albums.edit', [$album->album_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['galleries.index', 'galleries.editable', 'galleries.destroy', 'galleries.togglePublish']))
                    {!! renderLinkButton(
                        __('cmsadmin::models/galleries.singular'),
                        route('cmsadmin.galleries.index', [$album->album_id]),
                        'image far',
                        'secondary',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('albums.destroy'))
                    {!! renderDeleteBtn('cmsadmin.albums.destroy', $album->album_id, $album->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
