@if (checkMenuAccess('banners', 'cmsAdmin') ||
        checkMenuAccess('pages', 'cmsAdmin') ||
        checkMenuAccess('news', 'cmsAdmin') ||
        checkMenuAccess('posts', 'cmsAdmin') ||
        checkMenuAccess('blogs', 'cmsAdmin') ||
        checkMenuAccess('albums', 'cmsAdmin') ||
        checkMenuAccess('videoAlbums', 'cmsAdmin') ||
        checkMenuAccess('faqs', 'cmsAdmin') ||
        checkMenuAccess('testimonials', 'cmsAdmin') ||
        checkMenuAccess('resources', 'cmsAdmin'))
    @if (checkMenuAccess('banners', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/banner', $bannerPublishCount, $bannerCount, __('cmsadmin::models/banners.singular'), 'bg-dark') !!}
    @endif
    @if (checkMenuAccess('pages', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/page', $pagePublishCount, $pageCount, __('cmsadmin::models/pages.plural'), 'bg-info') !!}
    @endif
    @if (checkMenuAccess('news', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/news', $newsPublishCount, $newsCount, __('cmsadmin::models/news.plural'), 'bg-yellow') !!}
    @endif
    @if (checkMenuAccess('posts', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/post', $postPublishCount, $postCount, __('cmsadmin::models/posts.plural'), 'bg-danger') !!}
    @endif
    @if (checkMenuAccess('blogs', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/blog', $blogPublishCount, $blogCount, __('cmsadmin::models/blogs.plural'), 'bg-blue') !!}
    @endif
    @if (checkMenuAccess('albums', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/album', $albumPublishCount, $albumCount, __('cmsadmin::models/albums.singular'), 'bg-maroon') !!}
    @endif
    @if (checkMenuAccess('videoAlbums', 'cmsAdmin'))
        {!! renderDashboardCountHtml(
            'cmsadmin/video-album',
            $videoAlbumPublishCount,
            $videoAlbumCount,
            __('cmsadmin::models/video_albums.singular'),
            'bg-gradient-info',
        ) !!}
    @endif
    @if (checkMenuAccess('faqs', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/faq', $faqPublishCount, $faqCount, __('cmsadmin::models/faqs.singular'), 'bg-purple') !!}
    @endif
    @if (checkMenuAccess('testimonials', 'cmsAdmin'))
        {!! renderDashboardCountHtml(
            'cmsadmin/testimonial',
            $testimonialPublishCount,
            $testimonialCount,
            __('cmsadmin::models/testimonials.singular'),
            'bg-orange',
        ) !!}
    @endif
    @if (checkMenuAccess('resources', 'cmsAdmin'))
        {!! renderDashboardCountHtml('cmsadmin/resource', $resourcePublishCount, $resourceCount, __('cmsadmin::models/resources.singular'), 'bg-navy') !!}
    @endif
@endif
