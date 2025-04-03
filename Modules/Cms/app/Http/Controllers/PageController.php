<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\PageHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class PageController extends FrontendController
{
    /**
     * Display a listing of the page.
     */
    public function index($slug)
    {
        $page = PageHelper::getPageData($slug);
        if (empty($page)) {
            abort(404);
        }
        $pageDetails = $page?->details;

        $this->seo->description = $page->meta_description ?: $this->seo->description;
        $this->seo->keyword = $page->meta_keyword ?: $this->seo->keyword;
        $this->seo->title = $page->page_title ?: $this->seo->title;

        return view('cms::page.index')->with('page', $page)->with('pageDetails', $pageDetails);
    }
}
