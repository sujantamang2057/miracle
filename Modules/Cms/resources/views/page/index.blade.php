@extends('cms::layouts.master')

@if ($page)
    @section('content_header')
        <section class="sc-breadcrumb">
            <div class="container-fluid">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="index.php">{{ __('cms::general.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page->page_title }}</li>
                    </ol>
                </nav>
            </div>
        </section>
    @endsection

    @section('content')
        <div class="news-campaign-page">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-0">
                    <div class="">
                        <div class="sc-title text-uppercase fn-raleway">{{ $page->page_title }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-10 px-xxl-0 mx-auto pb-5">
                <div class="row">
                    @if ($page->banner_image)
                        @php
                            $isImage = $page->banner_image;
                            if (!empty($isImage)) {
                                $imagePath = storage_path('tmp/' . $isImage);
                                
                                if (file_exists($imagePath)) {
                                    $bannerImage = imageToBase64(storage_path('tmp/' . $isImage));
                                } else {
                                    $bannerImg = $page->banner_image ?? '';
                                    $bannerImgPath = storage_path(PAGE_FILE_PATH . DS . IMAGE_WIDTH_1200 . DS . $bannerImg);
                                    $bannerImgUrl = STORAGE_DIR_NAME . '/' . PAGE_FILE_DIR_NAME . '/' . IMAGE_WIDTH_1200 . '/' . $bannerImg;
                                    $bannerImage = file_exists($bannerImgUrl) ? asset($bannerImgUrl) : asset(DEFAULT_IMAGE_SIZE_1650);
                                }
                            } else {
                                $bannerImage = asset(DEFAULT_IMAGE_SIZE_1650);
                            }

                        @endphp
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <figure class="position-relative aspect-ratio-5 mb-0">
                                    <img src="{{ $bannerImage }}" alt="{{ $page->page_title }}">

                                </figure>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-{{ $page->banner_image ? '6' : '12' }}">
                        @if ($page->page_type == 2)
                            @if (isset($page->page_id) == false)
                                {!! Blade::render($page->description) !!}
                            @else
                                @php
                                    $componentName = 'pages_dynamic.' . $page->page_id . '_page';
                                @endphp
                                <x-dynamic-component class="mt-4" :component="$componentName" :page="$page" />
                            @endif
                        @else
                            <div class="editorData">
                                {!! $page->description !!}
                            </div>
                        @endif
                    </div>
                    @if (isset($pageDetails))
                        @if ($pageDetails)
                            @foreach ($pageDetails as $key => $detail)
                                {!! detailHtml($detail, PAGE_FILE_DIR_NAME, IMAGE_WIDTH_800) !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endsection
@endif
