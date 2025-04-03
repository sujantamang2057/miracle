@if (checkMenuAccess('banners', 'cmsAdmin') ||
        checkMenuAccess('news', 'cmsAdmin') ||
        checkMenuAccess('posts', 'cmsAdmin') ||
        checkMenuAccess('blogs', 'cmsAdmin') ||
        checkMenuAccess('albums', 'cmsAdmin') ||
        checkMenuAccess('videoAlbums', 'cmsAdmin') ||
        checkMenuAccess('faqs', 'cmsAdmin') ||
        checkMenuAccess('testimonials', 'cmsAdmin') ||
        checkMenuAccess('resources', 'cmsAdmin'))
    <div class="cleardiv">
        <div class="row">
            @if (checkMenuAccess('banners', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.banner', ['bannerData' => $bannerData])
                </div>
            @endif
            @if (checkMenuAccess('news', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.news', ['newsData' => $newsData])
                </div>
            @endif
            @if (checkMenuAccess('posts', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.post', ['postData' => $postData])
                </div>
            @endif
            @if (checkMenuAccess('blogs', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.blog', ['blogData' => $blogData])
                </div>
            @endif
            @if (checkMenuAccess('albums', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.imageAlbum', [
                        'imageAlbumData' => $imageAlbumData,
                    ])
                </div>
            @endif
            @if (checkMenuAccess('videoAlbums', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.videoAlbum', [
                        'videoAlbumData' => $videoAlbumData,
                    ])
                </div>
            @endif
            @if (checkMenuAccess('faqs', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.faq', ['faqData' => $faqData])
                </div>
            @endif
            @if (checkMenuAccess('testimonials', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.testimonial', [
                        'testimonialData' => $testimonialData,
                    ])
                </div>
            @endif
            @if (checkMenuAccess('resources', 'cmsAdmin'))
                <div class="col-md-4 mt-3">
                    @include('sys::dashboard.__includes.resource', ['resourceData' => $resourceData])
                </div>
            @endif
        </div>
    </div>
@endif
