@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.testimonials') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="testimonials-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.testimonials') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">{{ __('cms::general.previous_testimonials') }}</p>
                </div>
            </div>
        </div>

        <section class="sc-contact-page py-sm-5 py-3">
            @if ($testimonials && $testimonials->count())
                <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                    <div class="row row-gap-3 testimonial-list">
                        @foreach ($testimonials as $key => $testimonial)
                            @php
                                $testimonialImg = $testimonial->tm_profile_image ?? '';
                                $testimonialImgPath = storage_path(TESTIMONIAL_FILE_PATH . DS . IMAGE_WIDTH_200 . DS . $testimonialImg);
                                $testimonialImgUrl =
                                    STORAGE_DIR_NAME . '/' . TESTIMONIAL_FILE_DIR_NAME . '/' . IMAGE_WIDTH_200 . '/' . $testimonialImg;
                            @endphp

                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="testimonial h-100">
                                    <figure class="mx-auto">
                                        @if ($testimonial->tm_profile_image && file_exists($testimonialImgPath))
                                            <img src="{{ asset($testimonialImgUrl) }}" alt="{{ $testimonial->tm_name }}" />
                                        @else
                                            <img src="{{ asset('img/icons/user-bg.png') }}" alt="{{ $testimonial->tm_name }}" />
                                        @endif
                                    </figure>

                                    <h2 class="date fw-bold color-brown-800 mb-0 text-center">
                                        {{ $testimonial->tm_name }}</h2>

                                    @if ($testimonial->tm_designation)
                                        <h2 class="para-text color-brown-800 fw-normal m-0 text-center">
                                            {{ $testimonial->tm_designation }}</h2>
                                    @endif
                                    <h2 class="fs-6 color-brown-700 fw-normal m-0 text-center">
                                        {{ $testimonial->tm_company ?? '' }}</h2>

                                    <div class="description para-text color-brown-700 text-center">
                                        {{ $testimonial->tm_testimonial }} </div>

                                    @if ($testimonial->sns_fb || $testimonial->sns_twitter || $testimonial->sns_instagram || $testimonial->tm_youtube)
                                        <div class="socail-media d-flex mx-auto gap-2">
                                            @if ($testimonial->sns_fb)
                                                <a class="mt-3" href="{{ $testimonial->sns_fb }}" target="_blank"><i class="bi bi-facebook"></i></a>
                                            @endif
                                            @if ($testimonial->sns_twitter)
                                                <a class="mt-3" href="{{ $testimonial->sns_twitter }}" target="_blank"><i
                                                        class="bi bi-twitter"></i></a>
                                            @endif
                                            @if ($testimonial->sns_instagram)
                                                <a class="mt-3" href="{{ $testimonial->sns_instagram }}" target="_blank"><i
                                                        class="bi bi-instagram"></i></a>
                                            @endif
                                            @if ($testimonial->sns_youtube)
                                                <a class="mt-3" href="{{ $testimonial->sns_youtube }}" target="_blank"><i
                                                        class="bi bi-youtube"></i></a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if ($testimonials->total() > $testimonials->perPage())
                            <nav aria-label="Pagination" class="col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                                {{ $testimonials->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
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
        </section>
    </div>
@endsection
