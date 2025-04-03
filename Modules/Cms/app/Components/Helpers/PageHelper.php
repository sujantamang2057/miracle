<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Page;

class PageHelper
{
    public static function getPageData($slug)
    {
        if (empty($slug)) {
            return null;
        }
        $data = Page::published()
            ->with(['details' => function ($q) {
                $q->published()->limit(10)->orderBy('show_order', 'ASC');
            }])
            ->where('slug', $slug)
            ->whereDate('published_date', '<=', TODAY)
            ->first();

        return $data;
    }

    public static function getPageTypeById($id = null)
    {
        $type = Page::where('page_id', $id)->first();

        return $type->page_type;
    }
}
