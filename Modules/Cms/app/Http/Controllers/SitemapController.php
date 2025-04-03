<?php

namespace Modules\Cms\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Cms\app\Components\Helpers\BlogHelper;
use Modules\Cms\app\Components\Helpers\NewsHelper;
use Modules\Cms\app\Components\Helpers\PostHelper;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap.
     */
    public function index()
    {
        $posts = PostHelper::getPostDetails(null, null, TOP_PAGE_POST_LIMIT);
        $news = NewsHelper::getNewsDetails(null, null, TOP_PAGE_NEWS_LIMIT);
        $blogs = BlogHelper::getBlogData(null, null, TOP_PAGE_BLOG_LIMIT);

        return view('cms::sitemap.index')
            ->with('posts', $posts)
            ->with('blogs', $blogs)
            ->with('news', $news);
    }
}
