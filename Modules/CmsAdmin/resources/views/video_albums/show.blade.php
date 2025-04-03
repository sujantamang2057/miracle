@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('video_album_detail', $videoAlbum) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/video_albums.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::video_albums.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.videoAlbums.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['videoAlbums.create', 'videoAlbums.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.videoAlbums.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['videoAlbums.edit', 'videoAlbums.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.videoAlbums.edit', [$videoAlbum->album_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['galleries.index', 'galleries.editable', 'galleries.destroy', 'galleries.togglePublish']))
                    {!! renderLinkButton(
                        __('cmsadmin::models/galleries.singular'),
                        route('cmsadmin.videoGalleries.index', [$videoAlbum->album_id]),
                        'image far',
                        'secondary',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('videoAlbums.destroy'))
                    {!! renderDeleteBtn('cmsadmin.videoAlbums.destroy', $videoAlbum->album_id, $videoAlbum->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
