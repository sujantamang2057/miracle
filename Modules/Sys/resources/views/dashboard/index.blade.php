@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('dashboard') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('sys::models/dashboard.singular') }}
                        <small>{{ __('sys::models/dashboard.fields.control_panel') }}</small>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="site-index">
            <!-- Content Header (Page header) -->

            <!-- Main content -->

            <!-- statistics -->
            <div class="statistics">
                <div class="row">
                    @include('sys::dashboard.__statistics', [
                        'pagePublishCount' => $pagePublishCount,
                        'pageCount' => $pageCount,
                        'postPublishCount' => $postPublishCount,
                        'postCount' => $postCount,
                        'newsPublishCount' => $newsPublishCount,
                        'newsCount' => $newsCount,
                        'blogPublishCount' => $blogPublishCount,
                        'blogCount' => $blogCount,
                        'faqPublishCount' => $faqPublishCount,
                        'faqCount' => $faqCount,
                        'bannerPublishCount' => $bannerPublishCount,
                        'bannerCount' => $bannerCount,
                        'testimonialPublishCount' => $testimonialPublishCount,
                        'testimonialCount' => $testimonialCount,
                        'resourcePublishCount' => $resourcePublishCount,
                        'resourceCount' => $resourceCount,
                        'albumPublishCount' => $albumPublishCount,
                        'albumCount' => $albumCount,
                    ])
                </div>
            </div>
            <!-- statistics -->

            <!-- latest -->
            <div class="latest">
                @include('sys::dashboard.__latest', [
                    'postData' => $postData,
                    'faqData' => $faqData,
                    'blogData' => $blogData,
                    'newsData' => $newsData,
                    'bannerData' => $bannerData,
                    'testimonialData' => $testimonialData,
                    'resourceData' => $resourceData,
                    'imageAlbumData' => $imageAlbumData,
                    'videoAlbumData' => $videoAlbumData,
                ])

            </div>
            <!-- latest -->

        </div>
    </div>
@endsection
