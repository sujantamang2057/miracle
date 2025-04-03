<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\NewsCategory;

class NewsHelper
{
    public static function getCategoryList($slug = null, $limit = null, $pagination = false)
    {
        $query = NewsCategory::published();
        if (empty($slug)) {
            $query->whereHas('news', function ($q) {
                $q->published()->activeNow();
            });
        }
        $query->orderBy('show_order', 'desc');
        if (!empty($slug)) {
            $data = $query->where('slug', $slug)->first();
        } else {
            $data = $query->limit($limit)->get();
        }
        if (!empty($slug)) {
            $data = $query->where('slug', $slug)->first();
        } elseif ($pagination) {
            $data = $query->paginate(NEWS_CATEGORY_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getNewsDetails($slug = null, $categoryId = null, $limit = null, $pagination = false, $excludeNewsId = null, $freetext = null)
    {
        $isRelationPublished = function ($q) {
            $q->published();
        };

        $query = News::published()->activeNow()
            ->whereHas('category', $isRelationPublished)
            ->with([
                'category' => $isRelationPublished,
                'details' => $isRelationPublished,
            ]);

        if (!empty($slug)) {
            $query->where('slug', $slug);
        }
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        if (!empty($excludeNewsId)) {
            $query->where('news_id', '!=', $excludeNewsId);
        }

        // Add search condition
        if (!empty($freetext)) {
            $query->where(function ($q) use ($freetext) {
                $q->where('news_title', 'LIKE', "%{$freetext}%")
                    ->orWhere('slug', 'LIKE', "%{$freetext}%")
                    ->orWhere('description', 'LIKE', "%{$freetext}%");
            });
        }

        $query->orderBy('show_order', 'desc');

        if (!empty($slug)) {
            $data = $query->first();
        } elseif ($pagination) {
            $data = $query->paginate(NEWS_LIST_PAGINATE_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getNewsCategory($catId = null)
    {
        $category = NewsCategory::withTrashed()->find($catId);

        return $category;
    }
}
