<?php

namespace Modules\Cms\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\NewsHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class NewsController extends FrontendController
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $newsCategoryList = NewsHelper::getCategoryList(null, NEWS_CATEGORY_LIST_LIMIT, true);

        return view('cms::news.index')
            ->with('newsCategoryList', $newsCategoryList);
    }

    // render search page
    public function search(Request $request)
    {
        $freetext = $request->input('search');
        $newsList = NewsHelper::getNewsDetails(null, null, null, true, null, $freetext);
        $totalData = $newsList->total();

        return view('cms::news.search')
            ->with('totalData', $totalData)
            ->with('freetext', $freetext)
            ->with('newsList', $newsList);
    }

    public function newsList($slug)
    {
        $category = NewsHelper::getCategoryList($slug);

        if (empty($category)) {
            return redirect()->route('cms.news.index');
        }

        $newsList = NewsHelper::getNewsDetails(null, $category->category_id, null, true);

        return view('cms::news.news-list')->with(['category' => $category, 'newsList' => $newsList]);
    }

    public function detail($slug)
    {
        $news = NewsHelper::getNewsDetails($slug);

        if (!empty($news)) {
            $newsId = $news->news_id ?: '';
            $catId = $news->category_id ?: '';
            $relatedNews = !empty($catId) ? NewsHelper::getNewsDetails(null, $catId, RELATED_NEWS_LIMIT, false, $newsId) : null;

            return view('cms::news.detail')
                ->with('news', $news)
                ->with('relatedNews', $relatedNews);
        }

        return redirect()->route('cms.news.index');
    }
}
