@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('cms.news.index') }}">{{ __('cms::general.news') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('cms.news.newsList', ['slug' => $news?->category?->slug]) }}">{{ $news?->category?->category_name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $news->news_title }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="activity-detail-page position-relative">
        @php
            $newsImg = $news->banner_image ?? '';
            if (!empty($newsImg)) {
                $imagePath = storage_path('tmp/' . $newsImg);
                if (file_exists($imagePath)) {
                    $newImg = imageToBase64(storage_path('tmp/' . $newsImg));
                    $class = $newsImg  ? '' : 'no-image';
                } else {
                    $newsImgPath = storage_path(NEWS_FILE_PATH . DS . IMAGE_WIDTH_1355 . DS . $newsImg);
                    $newsImgURL = STORAGE_DIR_NAME . '/' . NEWS_FILE_DIR_NAME . '/' . IMAGE_WIDTH_1355 . '/' . $newsImg;
                    $newImg = file_exists($newsImgURL) ? asset($newsImgURL) : asset( DEFAULT_IMAGE_SIZE_600 );
                    $class = $newsImg && file_exists($newsImgPath) ? '' : 'no-image';
                }
            }

        @endphp
        @if ($newsImg)
            <section class="sc-sub-banner position-relative {{ $class }}">
                <figure>
                    <img src="{{ $newImg }}" alt="{{ $news->news_title }}" />
                </figure>
        @endif
        </section>

        <section class="sc-detail-information mt-5 pb-4 pt-5">
            <div class="col-xxl-10 px-xxl-0 clearfix mx-auto px-5">
                <div class="top-dt-info">
                    <div class="caption">
                        <div class="tour-name">
                            <p class="jp-text text-uppercase mb-0">
                                {{ dateFormatter($news->published_date, DATE_FORMAT_NO_ZERO) }}</p>
                            <p class="para-text text-uppercase fw-bold mb-4">{{ $news?->category?->category_name }}</p>
                            <p class="en-text fw-bold line-height-1 mb-0">{{ $news->news_title }}</p>
                        </div>
                    </div>
                    {!! $news->description !!}
                    @if (isset($news->details))
                        @if ($news->details)
                            @foreach ($news->details as $key => $detail)
                                {!! detailHtml($detail, NEWS_FILE_DIR_NAME, IMAGE_WIDTH_800) !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </section>

        @include('cms::news.recommendation')
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
