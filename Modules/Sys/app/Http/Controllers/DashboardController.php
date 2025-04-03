<?php

namespace Modules\Sys\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Models\Banner;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\Faq;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\Resource;
use Modules\CmsAdmin\app\Models\Testimonial;
use Modules\CmsAdmin\app\Models\VideoAlbum;
use Modules\Common\app\Http\Controllers\BackendController;

class DashboardController extends BackendController
{
    public function index()
    {
        $activewhere = ['publish' => 1];

        $pagePublishCount = countRecords('Modules\CmsAdmin\app\Models\Page', $activewhere);
        $pageCount = countRecords('Modules\CmsAdmin\app\Models\Page');
        $postPublishCount = countRecords('Modules\CmsAdmin\app\Models\Post', $activewhere);
        $postCount = countRecords('Modules\CmsAdmin\app\Models\Post');
        $newsPublishCount = countRecords('Modules\CmsAdmin\app\Models\News', $activewhere);
        $newsCount = countRecords('Modules\CmsAdmin\app\Models\News');
        $blogPublishCount = countRecords('Modules\CmsAdmin\app\Models\Blog', $activewhere);
        $blogCount = countRecords('Modules\CmsAdmin\app\Models\Blog');
        $faqPublishCount = countRecords('Modules\CmsAdmin\app\Models\Faq', $activewhere);
        $faqCount = countRecords('Modules\CmsAdmin\app\Models\Faq');
        $bannerPublishCount = countRecords('Modules\CmsAdmin\app\Models\Banner', $activewhere);
        $bannerCount = countRecords('Modules\CmsAdmin\app\Models\Banner');
        $testimonialPublishCount = countRecords('Modules\CmsAdmin\app\Models\Testimonial', $activewhere);
        $testimonialCount = countRecords('Modules\CmsAdmin\app\Models\Testimonial');
        $resourcePublishCount = countRecords('Modules\CmsAdmin\app\Models\Resource', $activewhere);
        $resourceCount = countRecords('Modules\CmsAdmin\app\Models\Resource');
        $albumPublishCount = countRecords('Modules\CmsAdmin\app\Models\Album', $activewhere);
        $albumCount = countRecords('Modules\CmsAdmin\app\Models\Album');
        $videoAlbumPublishCount = countRecords('Modules\CmsAdmin\app\Models\VideoAlbum', $activewhere);
        $videoAlbumCount = countRecords('Modules\CmsAdmin\app\Models\VideoAlbum');
        $postData = Post::orderBy('show_order', 'desc')->limit(BACKEND_POST_LIMIT)->get();
        $newsData = News::orderBy('show_order', 'desc')->limit(BACKEND_NEWS_LIMIT)->get();
        $blogData = Blog::orderBy('show_order', 'desc')->limit(BACKEND_BLOG_LIMIT)->get();
        $faqData = Faq::orderBy('show_order', 'desc')->limit(BACKEND_FAQ_LIMIT)->get();
        $bannerData = Banner::orderBy('show_order', 'desc')->limit(BACKEND_BANNER_LIMIT)->get();
        $testimonialData = Testimonial::orderBy('show_order', 'desc')->limit(BACKEND_TESTIMONIAL_LIMIT)->get();
        $resourceData = Resource::orderBy('show_order', 'desc')->limit(BACKEND_RESOURCE_LIMIT)->get();
        $imageAlbumData = Album::orderBy('show_order', 'desc')->limit(BACKEND_IMAGE_ALBUM_LIMIT)->get();
        $videoAlbumData = VideoAlbum::orderBy('show_order', 'desc')->limit(BACKEND_VIDEO_ALBUM_LIMIT)->get();

        return view('sys::dashboard.index')
            ->with('pageCount', $pageCount)
            ->with('pagePublishCount', $pagePublishCount)
            ->with('postPublishCount', $postPublishCount)
            ->with('postCount', $postCount)
            ->with('newsPublishCount', $newsPublishCount)
            ->with('newsCount', $newsCount)
            ->with('blogPublishCount', $blogPublishCount)
            ->with('blogCount', $blogCount)
            ->with('faqPublishCount', $faqPublishCount)
            ->with('faqCount', $faqCount)
            ->with('bannerPublishCount', $bannerPublishCount)
            ->with('bannerCount', $bannerCount)
            ->with('testimonialPublishCount', $testimonialPublishCount)
            ->with('testimonialCount', $testimonialCount)
            ->with('resourcePublishCount', $resourcePublishCount)
            ->with('resourceCount', $resourceCount)
            ->with('albumPublishCount', $albumPublishCount)
            ->with('albumCount', $albumCount)
            ->with('videoAlbumPublishCount', $videoAlbumPublishCount)
            ->with('videoAlbumCount', $videoAlbumCount)
            ->with('postData', $postData)
            ->with('faqData', $faqData)
            ->with('newsData', $newsData)
            ->with('blogData', $blogData)
            ->with('bannerData', $bannerData)
            ->with('testimonialData', $testimonialData)
            ->with('resourceData', $resourceData)
            ->with('imageAlbumData', $imageAlbumData)
            ->with('videoAlbumData', $videoAlbumData);
    }

    public function locale(Request $request)
    {
        $defaultLocale = config('app.locale');
        $referer = $request->server('HTTP_REFERER');
        $locale = $request->has('locale') ? $request->post('locale') : $defaultLocale;
        if (isset($locale) && Arr::exists(config('app.available_locales'), $locale)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        if ($referer) {
            return redirect($referer);
        }
    }
}
