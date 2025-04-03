@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.blog') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="news-campaign-page sc-tour-list">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-0">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.blog') }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-end text-green">
                    <span><span class="span-big" id="total-count"></span>pieces in total</span>
                </div>
            </div>
            <div class="search-input position-relative mx-auto">
                {!! Form::open(['route' => ['cms.blogs.search'], 'method' => 'post']) !!}
                <input type="text" name="search" id="search" placeholder="Search popular blogs" class="w-100">
                <button type="submit" class="search-item position-absolute top-50 w-100 d-flex justify-content-center align-items-center">
                    <i class="bi bi-search"></i>
                </button>
                {!! Form::close() !!}
            </div>
        </div>

        @php
            $totalActiveBlog = 0;
        @endphp
        @if ($blogCatList && $blogCatList->count())
            @foreach ($blogCatList as $key => $blogCat)
                <section class="sc-news-campaign mb-lg-5 mb-3">
                    <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                        <div class="news-title fw-bold">
                            <div class="d-flex align-items-center">
                                {{ $blogCat->cat_title }}
                                <svg width="49" height="35" viewBox="0 0 49 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M44.5 0C44.5 0 53.9995 0 43.4995 0C32.9995 0 30.4995 6 23.9995 14C17.4995 22 13.9995 26.4167 10.4995 30.5C6.99954 34.5833 0.499538 35 0.499538 35H44.5V0Z"
                                        fill="#fff7e2"></path>
                                </svg>
                            </div>
                            @if (!empty($blogCat->cat_slug))
                                <a href="{{ route('cms.blogs.blogList', ['slug' => $blogCat->cat_slug]) }}"
                                    class="view-more-btn-brown text-decoration-none rounded-pill me-3 ms-auto">
                                    <span>{{ __('cms::general.view_more') }}</span>
                                </a>
                            @else
                                <a href="{{ route('cms.blogs.index') }}" class="view-more-btn-brown text-decoration-none rounded-pill me-3 ms-auto">
                                    <span>{{ __('cms::general.view_more') }}</span>
                                </a>
                            @endif
                        </div>
                        <div class="articles-group">
                            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 pt-4">
                                @php
                                    $blogList = $blogCat->activeBlogs(BLOG_LIST_LIMIT)->get();
                                    $totalActiveBlog += $blogList?->count();
                                @endphp
                                @if ($blogList?->count())
                                    @foreach ($blogList as $index => $blog)
                                        <div class="hover-effect mb-sm-5 mb-3">
                                            <a href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}" class="hover-effect">
                                                <figure class="mb-2">
                                                    @php
                                                        $blogImg = $blog->thumb_image ?? '';
                                                        $blogImgPath = storage_path(BLOG_FILE_PATH . DS . IMAGE_WIDTH_800 . DS . $blogImg);
                                                        $blogImgURL =
                                                            STORAGE_DIR_NAME . '/' . BLOG_FILE_DIR_NAME . '/' . IMAGE_WIDTH_800 . '/' . $blogImg;
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
                    </div>
                </section>
            @endforeach
            <nav aria-label="Pagination" class="pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                {{ $blogCatList->links() }}
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
    @push('page_scripts')
        <script>
            $(document).ready(function() {
                document.getElementById('total-count').textContent = {{ $totalActiveBlog }}
            });
        </script>
    @endpush
@endsection
