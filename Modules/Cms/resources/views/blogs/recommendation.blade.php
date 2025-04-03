@if ($relatedBlogs && $relatedBlogs->count() > 0)
    <section class="sc-recommeded-article mb-lg-5 mb-3 overflow-hidden">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-center mb-sm-5 mb-3">
                <div class="text-center">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.related_blogs') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">{{ __('cms::general.popular_blogs') }}</p>
                </div>
            </div>
            <div class="recommended-slider">
                @foreach ($relatedBlogs as $key => $relatedData)
                    <a href="{{ route('cms.blogs.detail', ['slug' => $relatedData->slug]) }} " class="item d-block text-decoration-none">
                        <figure class="shadow-sm">
                            @php
                                $relatedDataImg = $relatedData->thumb_image ?? '';
                                $relatedDataPath = storage_path(BLOG_FILE_PATH . DS . IMAGE_WIDTH_360 . DS . $relatedDataImg);
                                $relatedDataURL = STORAGE_DIR_NAME . '/' . BLOG_FILE_DIR_NAME . '/' . IMAGE_WIDTH_360 . '/' . $relatedDataImg;
                                $relatedImg = $relatedDataImg && file_exists($relatedDataPath) ? $relatedDataURL : DEFAULT_IMAGE_SIZE_600;

                            @endphp
                            @if ($relatedData->thumb_image && file_exists($relatedDataPath))
                                <img src="{{ asset($relatedDataURL) }}" alt="{{ $relatedData->title }}" />
                            @else
                                <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $relatedData->title }}" />
                            @endif

                            <div class="reco-category">
                                {{ $relatedData->cat?->cat_title }}</div>
                        </figure>
                        <figcaption class="">
                            <p class="date fw-semibold mb-0 mb-1">
                                {{ dateFormatter($relatedData->display_date, DATE_FORMAT_NO_ZERO) }}
                            </p>
                            <div class="fw-semibold fs-6 mb-2">
                                {{ $relatedData->title }}</div>
                        </figcaption>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
