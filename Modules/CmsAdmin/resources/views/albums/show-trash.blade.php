@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('album_trash_detail', $album) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/albums.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::albums.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('albums.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.albums.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['albums.create', 'albums.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.albums.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('albums.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $album->album_id,
                        route('cmsadmin.albums.restore', ['id' => $album->album_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('albums.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.albums.permanentDestroy', $album->album_id, $album->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
