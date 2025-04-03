<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\BlogCategory;

class BlogHelper
{
    public static function getCategoryList($slug = null, $limit = null, $pagination = false)
    {
        $query = BlogCategory::published();
        if (empty($slug)) {
            $query->whereHas('blogs', function ($q) {
                $q->published()->activeNow();
            });
        }

        if (!empty($slug)) {
            $data = $query->where('cat_slug', $slug)->first();
        } elseif ($pagination) {
            $data = $query->paginate(BLOG_CATEGORY_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getBlogData($slug = null, $categoryId = null, $limit = null, $pagination = false, $excludedBlog = null, $freetext = null)
    {
        $isRelationPublished = function ($q) {
            $q->published();

        };

        $query = Blog::published()->activeNow()
            ->whereHas('cat', $isRelationPublished)
            ->with([
                'cat' => $isRelationPublished,
                'details' => $isRelationPublished,
            ]);

        if (!empty($slug)) {
            $query->where('slug', $slug);
        }
        if (!empty($categoryId)) {
            $query->where('cat_id', $categoryId);
        }
        if (!empty($excludedBlog)) {
            $query->where('blog_id', '!=', $excludedBlog);
        }

        // Add search condition
        if (!empty($freetext)) {
            $query->where(function ($q) use ($freetext) {
                $q->where('title', 'LIKE', "%{$freetext}%")
                    ->orWhere('slug', 'LIKE', "%{$freetext}%")
                    ->orWhere('detail', 'LIKE', "%{$freetext}%")
                    ->orWhere('remarks', 'LIKE', "%{$freetext}%")
                    ->orWhere('video_url', 'LIKE', "%{$freetext}%");
            });
        }

        $query->orderBy('show_order', 'desc');

        if (!empty($slug)) {
            $data = $query->first();
        } elseif ($pagination) {
            $data = $query->paginate(BLOG_LIST_PAGINATE_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getBlogsCategory($catId = null)
    {

        $category = BlogCategory::withTrashed()->find($catId);

        return $category;
    }
}
