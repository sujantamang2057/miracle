@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('video_album_trash_detail', $videoAlbum) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/video_albums.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::video_albums.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('videoAlbums.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.videoAlbums.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['videoAlbums.create', 'videoAlbums.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.videoAlbums.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('videoAlbums.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $videoAlbum->album_id,
                        route('cmsadmin.videoAlbums.restore', ['id' => $videoAlbum->album_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('videoAlbums.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.videoAlbums.permanentDestroy', $videoAlbum->album_id, $videoAlbum->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
