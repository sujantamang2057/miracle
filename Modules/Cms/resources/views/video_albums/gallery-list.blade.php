@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('cms.videoAlbums.index') }}">{{ __('cms::general.video_gallery') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->album_name }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="video-list-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5 pb-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ $category->album_name }}</div>
                </div>
            </div>
            @if ($galleryList->count())
                <div class="row row-gap-4">
                    @foreach ($galleryList as $key => $gallery)
                        @php
                            $galleryImg = $gallery->feature_image ?? '';
                            $galleryImgPath = storage_path(VIDEO_ALBUM_FILE_PATH . DS . IMAGE_WIDTH_1200 . DS . $galleryImg);
                            $galleryImgUrl = STORAGE_DIR_NAME . '/' . VIDEO_ALBUM_FILE_DIR_NAME . '/' . IMAGE_WIDTH_1200 . '/' . $galleryImg;
                            $galleryImg = $galleryImg && file_exists($galleryImgPath) ? $galleryImgUrl : DEFAULT_IMAGE_SIZE_500;
                        @endphp

                        <div class="col-1 col-md-6 col-lg-3">
                            <div class="video-box position-relative">
                                <a href="{{ $gallery->video_url }}" class="glightbox" data-gallery="gallery1">
                                    <img src="{{ asset($galleryImg) }}" alt="{{ $gallery->caption }}" class="w-100" />
                                </a>
                                <a href="{{ $gallery->video_url }}" class="glightbox overlay position-absolute bottom-0 end-0 start-0 top-0">
                                    <i class="bi bi-play-fill position-absolute"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    @if ($galleryList->total() > $galleryList->perPage())
                        <nav aria-label="Pagination" class="col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                            {{ $galleryList->links() }}
                        </nav>
                    @endif
                </div>
            @else
                <div class="not-found-class">
                    <div class="fs-1 fw-bold color-theme text-center">
                        {{ __('common::messages.data_not_available') }}
                    </div>
                    <div class="fs-5 text-center">
                        <a href="{{ route('cms.home.index') }}" class="btn btn-success bg-color-green rounded-full">
                            <span>{{ __('cms::general.home') }}</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
@include('cms::__partial.glightbox-assets')
