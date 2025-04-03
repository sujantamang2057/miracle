@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.gallery') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="activity-page position-relative">
        <div class="activityCategoryListing">
            <section class="sc-select-tour-category position-relative">
                <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                    <div class="heading d-flex align-items-end justify-content-between">
                        <div class="">
                            <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.album_list') }}</div>
                        </div>
                    </div>
                    @if ($imageAlbum && $imageAlbum->count())
                        <div class="tour-category-list">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3">
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($imageAlbum as $key => $album)
                                    @php
                                        $counter++;
                                        $classKey = $key % 9; // Reset class key after every 9 items
                                        $aClass = $counter % 3 == 2 ? ' top-gap' : '';
                                        $galleryImgPath = storage_path(ALBUM_FILE_PATH . DS . IMAGE_WIDTH_360 . DS);
                                        $galleryImgUrl = STORAGE_DIR_NAME . '/' . ALBUM_FILE_DIR_NAME . '/' . IMAGE_WIDTH_360 . '/';
                                        $coverImagePath = $galleryImgPath . optional($album->coverImage)->image_name;
                                        $coverImageUrl =
                                            STORAGE_DIR_NAME . '/' . ALBUM_FILE_DIR_NAME . '/' . optional($album->coverImage)->image_name;
                                        $defaultImage = asset(DEFAULT_IMAGE_SIZE_900);

                                        // For image source and alt text
                                        if (optional($album->coverImage)->image_name && file_exists($coverImagePath)) {
                                            $imgSrc = asset($coverImageUrl);
                                            $imgAlt = $album->title;
                                        } else {
                                            $firstGalleryImage = $album->galleries->first(function ($gallery) use ($galleryImgPath) {
                                                return !empty($gallery->image_name) && file_exists($galleryImgPath . $gallery->image_name);
                                            });

                                            if ($firstGalleryImage) {
                                                $imgSrc = asset($galleryImgUrl . $firstGalleryImage->image_name);
                                                $imgAlt = $firstGalleryImage->caption;
                                            } else {
                                                $imgSrc = $defaultImage;
                                                $imgAlt = $album->title;
                                            }
                                        }
                                    @endphp
                                    <a href="{{ route('cms.imageAlbums.galleryList', ['slug' => $album->slug]) }}"
                                        class="hover-effect {{ $aClass }}">
                                        <figure class="position-relative mb-0 shadow-sm">
                                            <img class="main-img" src="{{ $imgSrc }}" alt="{{ $imgAlt }}" />
                                            <img class="bg-hover-img-change d-none" src="" alt="" />
                                            <figcaption class="{{ getImageAlbumFigClass($classKey) }}">
                                                <span class="en-text {{ getImageAlbumSpanClass($key) }}">{{ $album->title }}
                                                </span>
                                                <span class="jp-text">{!! trimText($album->detail, ALBUM_TRIM_LEN, 'all') !!}
                                                </span>
                                            </figcaption>
                                            <div class="view-more-btn text-uppercase">
                                                <span>{{ __('cms::general.view_more') }}</span>
                                            </div>
                                        </figure>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @if ($imageAlbum->total() > $imageAlbum->perPage())
                            <nav aria-label="Pagination" class="pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                                {{ $imageAlbum->links() }}
                            </nav>
                        @endif
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
            </section>
        </div>
        <div class="bg-hover-img bg-hover-img-gallery">
            <img src="" alt="" />
        </div>
    </div>
@endsection
