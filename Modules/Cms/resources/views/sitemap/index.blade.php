@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.sitemap') }}</li>
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
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.sitemap') }}</div>
                </div>
            </div>
        </div>
        <section class="sitemap-page mb-5">
            <div class="container">
                <ul class="nav">
                    <li class="nav-item col-md-12 mb-3">
                        <a class="nav-link text-dark" href="{{ url('/') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>
                            TOP
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ url('about-us') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>About Us
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.imageAlbums.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.gallery') }}
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.videoAlbums.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.video') }}
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.faqs.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.faq') }}
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.resources.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.resources') }}
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.testimonials.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.testimonials') }}
                        </a>
                    </li>
                    <li class="nav-item col-12 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.contact.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cmsadmin::models/contacts.singular') }}
                        </a>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.posts.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.post') }}
                        </a>
                        <ul class="nav flex-column ms-4">
                            @foreach ($posts as $key => $post)
                                <li class="nav-item">
                                    <a class="nav-link text-dark ps-4" href="{{ route('cms.posts.detail', ['slug' => $post->slug]) }}">
                                        <i class="bi bi-circle me-2">&nbsp;</i>{{ $post->post_title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.blogs.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.blog') }}
                        </a>
                        <ul class="nav flex-column ms-4">
                            @foreach ($blogs as $key => $blog)
                                <li class="nav-item">
                                    <a class="nav-link text-dark ps-4" href="{{ route('cms.blogs.detail', ['slug' => $blog->slug]) }}">
                                        <i class="bi bi-circle me-2">&nbsp;</i>{{ $blog->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item col-4 mb-2 ps-4">
                        <a class="nav-link text-dark" href="{{ route('cms.news.index') }}">
                            <i class="bi bi-caret-right-fill me-2">&nbsp;</i>{{ __('cms::general.news') }}
                        </a>
                        <ul class="nav flex-column ms-4">
                            @foreach ($news as $key => $news)
                                <li class="nav-item">
                                    <a class="nav-link text-dark ps-4" href="{{ route('cms.news.detail', ['slug' => $news->slug]) }}">
                                        <i class="bi bi-circle me-2">&nbsp;</i>{{ $news->news_title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </section>
    </div>
@endsection
