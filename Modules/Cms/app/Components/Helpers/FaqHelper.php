<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\FaqCategory;

class FaqHelper
{
    // display faq data

    public static function getFaqList($catID = null, $limit = null)
    {
        $query = FaqCategory::published();
        if (empty($catID)) {
            $query->whereHas('faq', function ($q) {
                $q->published();
            });
        }
        $query->orderBy('show_order', 'desc');
        if (!empty($catID)) {
            $data = $query->where('faq_cat_id', $catID)->first();
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }
}
