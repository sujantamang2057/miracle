<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\Sys\app\Models\Sns;

class SnsHelper
{
    // get sns Module Data
    public static function getSns($limit = null)
    {
        $query = Sns::published()
            ->orderBy('show_order', 'desc')
            ->limit($limit)
            ->get();

        return $query;
    }
}
