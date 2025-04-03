@if ($relatedNews && $relatedNews->count() > 0)
    <section class="sc-recommeded-article mb-lg-5 mb-3 overflow-hidden">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-center mb-sm-5 mb-3">
                <div class="text-center">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.recommended_news') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">{{ __('cms::general.popular_news') }}</p>
                </div>
            </div>
            <div class="recommended-slider">
                @foreach ($relatedNews as $key => $relatedData)
                    @php
                        $newsImg = $relatedData->feature_image ?? '';
                        $newsImgPath = storage_path(NEWS_FILE_PATH . DS . IMAGE_WIDTH_360 . DS . $newsImg);
                        $newsImgURL = STORAGE_DIR_NAME . '/' . NEWS_FILE_DIR_NAME . '/' . IMAGE_WIDTH_360 . '/' . $newsImg;
                        $newImg = $newsImg && file_exists($newsImgPath) ? $newsImgURL : DEFAULT_IMAGE_SIZE_600;
                    @endphp
                    <a href="{{ route('cms.news.detail', ['slug' => $relatedData->slug]) }}" class="item d-block text-decoration-none">
                        <figure class="shadow-sm">
                            @if ($newImg)
                                <img src="{{ asset($newImg) }}" alt="{{ $relatedData->news_title }}" />
                            @else
                                <img src="{{ DEFAULT_IMAGE_SIZE_600 }}" alt="{{ $relatedData->news_title }}" />
                            @endif
                            <div class="reco-category">{{ $relatedData?->category?->category_name }}</div>
                        </figure>
                        <figcaption class="">
                            <p class="date fw-semibold mb-0 mb-1">
                                {{ dateFormatter($relatedData->published_date, DATE_FORMAT_NO_ZERO) }}</p>
                            <div class="fw-semibold fs-6 mb-2">{{ $relatedData->news_title }}</div>
                        </figcaption>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
