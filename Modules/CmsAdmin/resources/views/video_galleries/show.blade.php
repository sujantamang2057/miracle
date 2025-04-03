@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('video_gallery_detail', $videoGallery->album, $videoGallery) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/video_galleries.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::video_galleries.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(
                    __('common::crud.back'),
                    route('cmsadmin.videoGalleries.index', $videoGallery->album_id),
                    'chevron-circle-left',
                    'warning',
                    '',
                ) !!}
                @if (checkCmsAdminPermissionList(['videoGalleries.create', 'videoGalleries.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.videoGalleries.create', $videoGallery->album_id), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['videoGalleries.edit', 'videoGalleries.update']))
                    {!! renderLinkButton(
                        __('common::crud.update'),
                        route('cmsadmin.videoGalleries.edit', [
                            'videoAlbum' => $videoGallery->album_id,
                            'gallery' => $videoGallery->video_id,
                        ]),
                        'edit',
                        'primary',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('videoGalleries.destroy'))
                    {!! renderDeleteBtn('cmsadmin.videoGalleries.destroy', $videoGallery->video_id, $videoGallery->reserved == 2, [
                        'videoAlbum' => $videoGallery->album_id,
                        'gallery' => $videoGallery->video_id,
                    ]) !!}
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
