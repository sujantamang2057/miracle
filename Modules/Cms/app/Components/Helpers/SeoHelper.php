<?php

namespace Modules\Cms\app\Components\Helpers;

use Illuminate\Support\Facades\App;
use Modules\CmsAdmin\app\Models\Seo;

class SeoHelper
{
    public static function getSeo($code)
    {
        $seo = Seo::where('code', $code)->first();
        $locale = App::getLocale();

        return (object) [
            'title' => $seo ? ($locale === 'ja' ? $seo->title_locale : $seo->title) : null,
            'description' => $seo ? ($locale === 'ja' ? $seo->description_locale : $seo->description) : null,
            'keyword' => $seo ? ($locale === 'ja' ? $seo->keyword_locale : $seo->keyword) : null,
        ];
    }
}
