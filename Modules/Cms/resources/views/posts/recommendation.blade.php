@if ($relatedPosts && $relatedPosts->count() > 0)
    <section class="sc-recommeded-article mb-lg-5 mb-3 overflow-hidden">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-center mb-sm-5 mb-3">
                <div class="text-center">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.recommended_posts') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">{{ __('cms::general.popular_posts') }}</p>
                </div>
            </div>
            <div class="recommended-slider">
                @foreach ($relatedPosts as $key => $relatedData)
                    @php
                        $postsImg = $relatedData->feature_image ?? '';
                        $postImgPath = storage_path(POST_FILE_PATH . DS . IMAGE_WIDTH_360 . DS . $postsImg);
                        $postImgURL = STORAGE_DIR_NAME . '/' . POST_FILE_DIR_NAME . '/' . IMAGE_WIDTH_360 . '/' . $postsImg;
                        $postImg = $postsImg && file_exists($postImgPath) ? $postImgURL : DEFAULT_IMAGE_SIZE_600;
                    @endphp
                    <a href="{{ route('cms.posts.detail', ['slug' => $relatedData->slug]) }}" class="item d-block text-decoration-none">
                        <figure class="shadow-sm">
                            @if ($postImg)
                                <img src="{{ asset($postImg) }}" alt="{{ $relatedData->post_title }}" />
                            @else
                                <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $relatedData->post_title }}" />
                            @endif
                            <div class="reco-category">{{ $relatedData?->category?->category_name }}</div>
                        </figure>
                        <figcaption class="">
                            <p class="date fw-semibold mb-0 mb-1">
                                {{ dateFormatter($relatedData->published_date, DATE_FORMAT_NO_ZERO) }}</p>
                            </p>
                            <div class="fw-semibold fs-6 mb-2">{{ $relatedData->post_title }}</div>
                        </figcaption>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
