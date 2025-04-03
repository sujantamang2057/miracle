<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\PostCategory;

class PostHelper
{
    // get PostCategory  Data

    public static function getCategoryList($slug = null, $limit = null, $pagination = false)
    {
        $query = PostCategory::published();
        if (empty($slug)) {
            $query->whereHas('posts', function ($q) {
                $q->published()->activeNow();
            });
        }

        $query->orderBy('show_order', 'desc');

        if (!empty($slug)) {
            $data = $query->where('slug', $slug)->first();
        } elseif ($pagination) {
            $data = $query->paginate(POST_CATEGORY_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getPostDetails($slug = null, $categoryId = null, $limit = null, $pagination = false, $excludePostId = null, $freetext = null)
    {
        $isRelnPublished = function ($q) {
            $q->published();
        };

        $query = Post::published()->activeNow()
            ->whereHas('category', $isRelnPublished)
            ->with([
                'category' => $isRelnPublished,
                'details' => $isRelnPublished,
            ]);

        if (!empty($slug)) {
            $query->where('slug', $slug);
        }
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($excludePostId)) {
            $query->where('post_id', '!=', $excludePostId);
        }

        // Add search condition
        if (!empty($freetext)) {
            $query->where(function ($q) use ($freetext) {
                $q->where('post_title', 'LIKE', "%{$freetext}%")
                    ->orWhere('slug', 'LIKE', "%{$freetext}%")
                    ->orWhere('description', 'LIKE', "%{$freetext}%");
            });
        }

        $query->orderBy('show_order', 'desc');

        if (!empty($slug)) {
            $data = $query->first();
        } elseif ($pagination) {
            $data = $query->paginate(POST_LIST_PAGINATE_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getPostCategory($catId = null)
    {
        $category = PostCategory::withTrashed()->find($catId);

        return $category;
    }
}
