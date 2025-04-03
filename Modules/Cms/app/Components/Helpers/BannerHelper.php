<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Banner;

class BannerHelper
{
    // get Banner Module Data
    public static function getBanner($limit = null)
    {
        $bannerQuery = Banner::published()
            ->orderBy('show_order', 'desc')
            ->limit($limit);

        return $bannerQuery->get();
    }
}
