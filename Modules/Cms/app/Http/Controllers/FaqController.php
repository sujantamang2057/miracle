<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\FaqHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class FaqController extends FrontendController
{
    /**
     * Display a listing of the faqCategoryData.
     */
    public function index()
    {
        $faqCategorylist = FaqHelper::getFaqList(null, FAQ_CATEGORY_LIST_LIMIT);

        return view('cms::faqs.index')
            ->with('faqCategorylist', $faqCategorylist);
    }
}
