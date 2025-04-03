@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('cms.imageAlbums.index') }}">{{ __('cms::general.gallery') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $album->title }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="faq-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ $album->title }}</div>
                </div>
            </div>
        </div>
        @if ($galleryImages && $galleryImages->count() > 0)
            <section class="sc-gallery-list-page pb-5">
                <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                    <div class="row row-gap-4">
                        @foreach ($galleryImages as $key => $galleryData)
                            @php
                                $galleryImg = $galleryData->image_name ?? '';
                                $galleryImgPath = storage_path(ALBUM_FILE_PATH . DS . IMAGE_WIDTH_600 . DS . $galleryImg);
                                $galleryImgUrl = STORAGE_DIR_NAME . '/' . ALBUM_FILE_DIR_NAME . '/' . IMAGE_WIDTH_600 . '/' . $galleryImg;
                                $fancyImgPath = storage_path(ALBUM_FILE_PATH . DS . IMAGE_WIDTH_800 . DS . $galleryImg);
                                $fancyImgUrl = STORAGE_DIR_NAME . '/' . ALBUM_FILE_DIR_NAME . '/' . IMAGE_WIDTH_800 . '/' . $galleryImg;
                                $galleryImg = $galleryImg && file_exists($galleryImgPath) ? $galleryImgUrl : DEFAULT_IMAGE_SIZE_600;
                                $fancyImg = $galleryImg && file_exists($fancyImgPath) ? $fancyImgUrl : DEFAULT_IMAGE_SIZE_600;
                            @endphp
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="image-box">
                                    <a href="{{ asset($fancyImg) }}" class="glightbox" data-gallery="gallery1"
                                        data-description="{{ $galleryData->caption }}" data-desc-position="right">
                                        <img src="{{ asset($galleryImg) }}" alt="{{ $galleryData->caption }}" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @if ($galleryImages->total() > $galleryImages->perPage())
                            <nav aria-label="Pagination" class="pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                                {{ $galleryImages->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        @else
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
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
@endsection
@include('cms::__partial.glightbox-assets')
