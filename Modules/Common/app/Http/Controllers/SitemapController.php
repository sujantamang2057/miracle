<?php

namespace Modules\Common\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\FaqCategory;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\Page;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\VideoAlbum;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap.
     */
    public function index()
    {
        $sitemap = Sitemap::create();
        $pages = Page::published()->get();
        foreach ($pages as $page) {
            $sitemap->add(Url::create("/pages/{$page->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        $posts = Post::published()->get();
        foreach ($posts as $post) {
            $sitemap->add(Url::create("/posts/{$post->slug}")
                ->setLastModificationDate(Carbon::create($post->updated_at))
            );
        }

        $blogs = Blog::published()->get();
        foreach ($blogs as $blog) {
            $sitemap->add(Url::create("/blogs/{$blog->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        $news = News::published()->get();
        foreach ($news as $new) {
            $sitemap->add(Url::create("/news/{$new->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        $albums = Album::published()->get();
        foreach ($albums as $album) {
            $sitemap->add(Url::create("/albums/{$album->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        $videoAlbums = VideoAlbum::published()->get();
        foreach ($videoAlbums as $videoAlbum) {
            $sitemap->add(Url::create("/video-albums/{$videoAlbum->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        $faqCategorys = FaqCategory::published()->get();
        foreach ($faqCategorys as $faqCategory) {
            $sitemap->add(Url::create("/faq-categorys/{$faqCategory->slug}")
                ->setLastModificationDate(Carbon::create($page->updated_at))
            );
        }

        // Render the sitemap to a string
        $sitemapContent = $sitemap->render();

        // Return the sitemap as an XML response
        return response($sitemapContent, 200)
            ->header('Content-Type', 'text/xml');
    }
}
