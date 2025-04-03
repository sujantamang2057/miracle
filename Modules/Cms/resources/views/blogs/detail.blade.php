@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('cms.blogs.index') }}">{{ __('cms::general.blog') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('cms.blogs.blogList', ['slug' => $blog?->cat?->cat_slug]) }}">{{ $blog?->cat?->cat_title }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $blog->title }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection
@section('content')
    <div class="activity-detail-page position-relative">
        @php

            $blogImg = $blog->image ?? '';
            if (!empty($blogImg)) {
                $imagePath = storage_path('tmp/' . $blogImg);
                if (file_exists($imagePath)) {
                    $blogImage = imageToBase64(storage_path('tmp/' . $blogImg));
                } else {
                    $blogImgPath = storage_path(BLOG_FILE_PATH . DS . IMAGE_WIDTH_1355 . DS . $blogImg);
                    $blogImgURL = STORAGE_DIR_NAME . '/' . BLOG_FILE_DIR_NAME . '/' . IMAGE_WIDTH_1355 . '/' . $blogImg;
                    $blogImage = file_exists($blogImgURL) ? asset($blogImgURL) : asset(DEFAULT_IMAGE_SIZE_600);
                }
            }

        @endphp
        @if (!empty($blogImage))
            <section class="sc-sub-banner position-relative">
                <figure>
                    <img src="{{ $blogImage }}" />
                </figure>
            </section>
        @endif

        <section class="sc-detail-information mt-5 pb-4 pt-5">
            <div class="col-xxl-10 px-xxl-0 clearfix mx-auto px-5">
                <div class="top-dt-info">
                    <div class="caption">
                        <div class="tour-name">
                            <p class="jp-text text-uppercase mb-0">
                                {{ dateFormatter($blog->display_date, DATE_FORMAT_NO_ZERO) }}
                            </p>
                            <p class="para-text text-uppercase fw-bold mb-0"> {{ $blog->cat->cat_title }}</p>
                            <p class="en-text fw-bold line-height-1 fs-1 mb-0 mb-3">{{ $blog->title }}</p>
                        </div>
                    </div>
                    {!! $blog->detail !!}
                    @if (isset($blog->details))
                        @if ($blog->details)
                            @foreach ($blog->details as $key => $detail)
                                {!! detailHtml($detail, BLOG_FILE_DIR_NAME, IMAGE_WIDTH_800) !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </section>
        @if (isset($relatedBlogs))
            @include('cms::blogs.recommendation')
        @endif
    </div>
@endsection
@include('cms::__partial.slick-assets')
@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // recommended-slider
            $('.recommended-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                fade: false,
                infinite: true,
                autoplay: true,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

        });
    </script>
@endpush
