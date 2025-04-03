<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Modules\CmsAdmin\app\DataTables\NewsDetailDataTable;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Repositories\NewsDetailRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class NewsDetailController extends BackendController
{
    public function __construct(NewsDetailRepository $newsDetailRepositoryRepo)
    {
        $this->moduleName = 'cmsadmin.newsDetails';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'destroy', 'togglePublish', 'reorder']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $newsDetailRepositoryRepo;
        $this->langFile = 'cmsadmin::models/news.news_multidata';
        $id = request()->route('news');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.newsDetails.index', ['news' => $id]);
    }

    /**
     * Display a listing of the newsDetail.
     */
    public function index($id, NewsDetailDataTable $newsDetailDataTable)
    {
        $news = News::find($id);

        if (empty($news)) {
            Flash::error(__('cmsadmin::models/news.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.news.index'));
        }

        return $newsDetailDataTable->render('cmsadmin::news_details.index', ['news' => $news]);
    }
}
