@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('cms.posts.index') }}">{{ __('cms::general.post') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('cms.posts.postList', ['slug' => $post?->category?->slug]) }}">{{ $post?->category?->category_name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $post->post_title }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="activity-detail-page position-relative">
        @php
            $postImg = $post->banner_image;
            if ($postImg) {
                $imagePath = storage_path('tmp/' . $postImg);
                if (file_exists($imagePath)) {
                    $bannerImg = imageToBase64(storage_path('tmp/' . $postImg));
                } else {
                    $postImgURL =  STORAGE_DIR_NAME . DS . POST_FILE_DIR_NAME . DS . $postImg;
                    $bannerImg = file_exists($postImgURL) ? asset($postImgURL) : asset( DEFAULT_IMAGE_SIZE_600 );
                   
                }
            }
        @endphp
        <section class="sc-sub-banner position-relative">
            @if ($postImg)
                <figure>
                    <img src="{{ $bannerImg }}" />

                </figure>
            @endif
        </section>

        <section class="sc-detail-information mt-5 pb-4 pt-5">
            <div class="col-xxl-10 px-xxl-0 clearfix mx-auto px-5">
                <div class="top-dt-info">
                    <div class="caption">
                        <div class="tour-name">
                            <p class="jp-text text-uppercase mb-0">
                                {{ dateFormatter($post->published_date, DATE_FORMAT_NO_ZERO) }}</p>
                            <p class="para-text text-uppercase fw-bold mb-0">{{ $post?->category?->category_name }}</p>
                            <p class="en-text fw-bold line-height-1 mb-0">{{ $post->post_title }}</p>
                        </div>
                    </div>
                    {!! $post->description !!}
                    @if (isset($post->details))
                        @if ($post->details)
                            @foreach ($post->details as $key => $detail)
                                {!! detailHtml($detail, POST_FILE_DIR_NAME, IMAGE_WIDTH_800) !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </section>
        @if (isset($relatedPosts))
            @include('cms::posts.recommendation')
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
