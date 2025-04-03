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
                <div class="sc-title text-uppercase fn-raleway">POST</div>
                <div class="d-flex align-items-center justify-content-end text-green">
                    <span><span class="span-big"><?= $totalData ?></span>pieces in total</span>
                </div>
            </div>
            <div class="search-input position-relative mx-auto">
                {!! Form::open(['route' => ['cms.posts.search'], 'method' => 'post']) !!}
                <input type="text" name="search" id="search" placeholder="Search popular posts" value="<?= $freetext ?>" class="w-100">
                <button type="submit" class="search-item position-absolute top-50 w-100 d-flex justify-content-center align-items-center">
                    <i class="bi bi-search"></i>
                </button>
                {!! Form::close() !!}
            </div>
        </div>

        <section class="sc-news-campaign mb-lg-5 mb-3">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="articles-group postListing">
                    <div class="fw-bold fs-2 mb-5">Search item :<?= $freetext ?? '' ?></div>
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 pt-3">
                        @foreach ($postList as $key => $post)
                            @php
                                $postsImg = $post->feature_image ?? '';
                                $postImgPath = storage_path(POST_FILE_PATH . DS . IMAGE_WIDTH_400 . DS . $postsImg);
                                $postImgURL = STORAGE_DIR_NAME . '/' . POST_FILE_DIR_NAME . '/' . IMAGE_WIDTH_400 . '/' . $postsImg;
                                $postImg = $postsImg && file_exists($postImgPath) ? $postImgURL : DEFAULT_IMAGE_SIZE_600;
                            @endphp
                            <div class="d-grid hover-effect">
                                <a href="{{ route('cms.posts.detail', ['slug' => $post->slug]) }}" class="hover-effect">
                                    <figure class="mb-2">
                                        @if ($postImg)
                                            <img src="{{ asset($postImg) }}" alt="{{ $post->post_title }}" />
                                        @else
                                            <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $post->post_title }}" />
                                        @endif
                                    </figure>
                                </a>
                                <div class="latest-info d-flex flex-column flex-fill">
                                    <div class="date fw-semibold mb-1">
                                        {{ dateFormatter($post->published_date, DATE_FORMAT_NO_ZERO) }}
                                    </div>
                                    <a href="{{ route('cms.posts.detail', ['slug' => $post->slug]) }}" class="text-decoration-none">
                                        <div class="date fw-semibold color-theme mb-1">
                                            {{ $post->post_title }}
                                        </div>
                                    </a>
                                    <P class="para-text">
                                        {!! trimText($post->description, POST_TRIM_LEN, 'all') !!}
                                    </P>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <nav aria-label="Pagination" class="postSearchPagination pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                        {{ $postList->appends(request()->except(['_token']))->links() }}
                    </nav>
                </div>
            </div>
        </section>
    </div>
@endsection
