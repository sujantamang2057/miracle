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
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->cat_title }}</li>
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
                    <div class="sc-title text-uppercase fn-raleway">{{ $category->cat_title }}</div>
                </div>
            </div>
        </div>

        <section class="sc-news-campaign mb-lg-5 mb-3">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="articles-group">
                    @if ($blogList)
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-group-1">
                            @foreach ($blogList as $key => $blog)
                                <div class="d-flex hover-effect gap-3">
                                    <figure>
                                        <a href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}" class="d-block">
                                            @php
                                                $blogImg = $blog->thumb_image ?? '';
                                                $blogImgPath = storage_path(BLOG_FILE_PATH . DS . IMAGE_WIDTH_600 . DS . $blogImg);
                                                $blogImgURL = STORAGE_DIR_NAME . '/' . BLOG_FILE_DIR_NAME . '/' . IMAGE_WIDTH_600 . '/' . $blogImg;
                                                $blogImg = $blogImg && file_exists($blogImgPath) ? $blogImgURL : DEFAULT_IMAGE_SIZE_600;
                                            @endphp
                                            @if ($blog->thumb_image && file_exists($blogImgPath))
                                                <img src="{{ asset($blogImgURL) }}" alt="{{ $blog->blog_title }}" />
                                            @else
                                                <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $blog->blog_title }}" />
                                            @endif
                                        </a>
                                    </figure>
                                    <div class="latest-info d-flex flex-column flex-fill">
                                        <div class="date fw-semibold mb-1">
                                            {{ dateFormatter($blog->display_date, DATE_FORMAT_NO_ZERO) }}
                                        </div>
                                        <a href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}" class="text-decoration-none">
                                            <div class="fw-semibold fs-5 color-theme mb-2">
                                                {{ $blog->title }}
                                            </div>
                                        </a>
                                        <div class="category-tag"><span> {{ $blog?->cat?->cat_title }}
                                            </span></div>
                                        <p class="para-text"> {!! trimText($blog->detail, BLOG_TRIM_LEN, 'all') !!}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <nav aria-label="Pagination" class="col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                            {{ $blogList->links() }}
                        </nav>
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
            </div>
    </div>
    </section>
    </div>
@endsection
