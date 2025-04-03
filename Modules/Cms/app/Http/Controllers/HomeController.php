<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\AlbumHelper;
use Modules\Cms\app\Components\Helpers\BannerHelper;
use Modules\Cms\app\Components\Helpers\BlogHelper;
use Modules\Cms\app\Components\Helpers\NewsHelper;
use Modules\Cms\app\Components\Helpers\PostHelper;
use Modules\Cms\app\Components\Helpers\TestimonialHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class HomeController extends FrontendController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = BannerHelper::getBanner(TOP_PAGE_BANNER_LIMIT);
        $posts = PostHelper::getPostDetails(null, null, TOP_PAGE_POST_LIMIT);
        $news = NewsHelper::getNewsDetails(null, null, TOP_PAGE_NEWS_LIMIT);
        $albums = AlbumHelper::getAlbumDetails(null, TOP_PAGE_ALBUM_LIMIT);
        $blogs = BlogHelper::getBlogData(null, null, TOP_PAGE_BLOG_LIMIT);
        $testimonials = TestimonialHelper::getTestimonial(TOP_PAGE_TESTIMONIAL_LIMIT);

        return view('cms::home.index')
            ->with('banners', $banners)
            ->with('posts', $posts)
            ->with('blogs', $blogs)
            ->with('news', $news)
            ->with('albums', $albums)
            ->with('testimonials', $testimonials);

    }
}
