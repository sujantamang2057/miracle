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
                    <li class="breadcrumb-item active" aria-current="page">Search</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="news-campaign-page sc-tour-list">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between pb-sm-4 pb-0">
                <div class="sc-title text-uppercase fn-raleway">BLOG</div>
                <div class="d-flex align-items-center justify-content-end text-green">
                    <span><span class="span-big"><?= $totalData ?></span>pieces in total</span>
                </div>
            </div>
            <div class="search-input position-relative mx-auto">
                {!! Form::open(['route' => ['cms.blogs.search'], 'method' => 'post']) !!}
                <input type="text" name="search" id="search" placeholder="Search popular blogs" value="<?= $freetext ?>" class="w-100">
                <button type="submit" class="search-item position-absolute top-50 w-100 d-flex justify-content-center align-items-center">
                    <i class="bi bi-search"></i>
                </button>
                {!! Form::close() !!}
            </div>
        </div>

        <section class="sc-news-campaign mb-lg-5 mb-3">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="articles-group blogListing">
                    <div class="fw-bold fs-2 mb-5">Search item :<?= $freetext ?? '' ?></div>
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 pt-3">
                        @foreach ($blogList as $key => $blog)
                            <div class="d-grid hover-effect">
                                <a href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}" class="hover-effect">
                                    <figure class="mb-2">
                                        @php
                                            $blogImg = $blog->thumb_image ?? '';
                                            $blogImgPath = storage_path(BLOG_FILE_PATH . DS . IMAGE_WIDTH_400 . DS . $blogImg);
                                            $blogImgURL = STORAGE_DIR_NAME . '/' . BLOG_FILE_DIR_NAME . '/' . IMAGE_WIDTH_400 . '/' . $blogImg;
                                            $blogImg = $blogImg && file_exists($blogImgPath) ? $blogImgURL : DEFAULT_IMAGE_SIZE_600;
                                        @endphp
                                        @if ($blog->thumb_image && file_exists($blogImgPath))
                                            <img src="{{ asset($blogImgURL) }}" alt="{{ $blog->blog_title }}" />
                                        @else
                                            <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $blog->blog_title }}" />
                                        @endif
                                    </figure>
                                </a>
                                <div class="latest-info d-flex flex-column flex-fill">
                                    <div class="date fw-semibold mb-1">
                                        {{ dateFormatter($blog->display_date, DATE_FORMAT_NO_ZERO) }}
                                    </div>
                                    <a href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}" class="text-decoration-none">
                                        <div class="date fw-semibold color-theme mb-1">
                                            {{ $blog->title }}
                                        </div>
                                    </a>
                                    <p class="para-text">
                                        {!! trimText($blog->detail, BLOG_TRIM_LEN, 'all') !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <nav aria-label="Pagination" class="blogSearchPagination pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                        {{ $blogList->appends(request()->except(['_token']))->links() }}
                    </nav>
                </div>
            </div>
        </section>
    </div>
@endsection
