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
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->category_name }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="news-campaign-list-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-0">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ $category->category_name }}</div>
                </div>
            </div>
        </div>

        <section class="sc-news-campaign mb-lg-5 mb-3">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="articles-group">
                    @if ($newsList)
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-group-1">
                            @foreach ($newsList as $key => $news)
                                @php
                                    $newsImg = $news->feature_image ?? '';
                                    $newsImgPath = storage_path(NEWS_FILE_PATH . DS . IMAGE_WIDTH_200 . DS . $newsImg);
                                    $newsImgURL = STORAGE_DIR_NAME . '/' . NEWS_FILE_DIR_NAME . '/' . IMAGE_WIDTH_200 . '/' . $newsImg;
                                    $newImg = $newsImg && file_exists($newsImgPath) ? $newsImgURL : DEFAULT_IMAGE_SIZE_600;
                                @endphp
                                <div class="d-flex hover-effect gap-3">
                                    <figure>
                                        <a href="{{ route('cms.news.detail', ['slug' => $news->slug]) }}" class="d-block">
                                            @if ($newImg)
                                                <img src="{{ asset($newImg) }}" alt="{{ $news->news_title }}" />
                                            @else
                                                <img src="{{ DEFAULT_IMAGE_SIZE_600 }}" alt="{{ $news->news_title }}" />
                                            @endif
                                        </a>
                                    </figure>
                                    <div class="latest-info d-flex flex-column flex-fill">
                                        <div class="date fw-semibold mb-1">
                                            {{ dateFormatter($news->published_date, DATE_FORMAT_NO_ZERO) }}
                                        </div>
                                        <a class="text-decoration-none" href="{{ route('cms.news.detail', ['slug' => $news->slug]) }}">
                                            <div class="fw-semibold fs-5 color-theme mb-2">{{ $news->news_title }}</div>
                                        </a>
                                        <div class="category-tag"><span>{{ $news?->category?->category_name }}</span></div>
                                        <p class="para-text">{!! trimText($news->description, NEWS_TRIM_LEN, 'all') !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <nav aria-label="Pagination" class="col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                            {{ $newsList->links() }}
                        </nav>
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
        </section>
    </div>
@endsection
