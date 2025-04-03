<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Resource;

class ResourceHelper
{
    public static function getResourceList($pagination = null, $limit = null, $year = true)
    {
        $query = Resource::published()
            ->orderBy('show_order', 'desc');

        if ($year) {
            $query = $query->whereBetween('created_at', ["{$year}-01-01", "{$year}-12-31"]);
        }

        if ($pagination) {
            $data = $query->paginate(RESOURCE_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }
}
