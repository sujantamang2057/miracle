<?php

namespace Modules\Common\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CmsAdmin\app\Models\Seo;
use Modules\Sys\app\Models\SiteSetting;

class FrontendController extends Controller
{
    protected $seo;

    protected $siteSetting;

    public function __construct()
    {
        // Apply Security
        if (env('SECURITY_CSP_FRONTEND_ENABLE', false) == true) {
            $this->middleware('secure');
        }

        $this->siteSetting = SiteSetting::__getSiteSettings();

        $locale = app()->getLocale();
        $seoCode = getSEOCode();

        $seo = Seo::published()->where('code', $seoCode)->first();
        $isJapaneseLocale = $locale === 'ja';
        $this->seo = (object) [
            'title' => $seo ? ($isJapaneseLocale ? $seo->title_locale : $seo->title) : null,
            'description' => $seo ? ($isJapaneseLocale ? $seo->description_locale : $seo->description) : null,
            'keyword' => $seo ? ($isJapaneseLocale ? $seo->keyword_locale : $seo->keyword) : null,
        ];

        view()->share([
            'seo' => $this->seo,
            'siteSetting' => $this->siteSetting,
        ]);
    }
}
