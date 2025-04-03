<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Modules\CmsAdmin\app\DataTables\PageDetailDataTable;
use Modules\CmsAdmin\app\Models\Page;
use Modules\CmsAdmin\app\Repositories\PageDetailRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class PageDetailController extends BackendController
{
    public function __construct(PageDetailRepository $pageDetailRepositoryRepo)
    {
        $this->moduleName = 'cmsadmin.pageDetails';

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

        $this->repository = $pageDetailRepositoryRepo;
        $this->langFile = 'cmsadmin::models/pages.page_multidata';
        $id = request()->route('page');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.pageDetails.index', ['page' => $id]);
    }

    /**
     * Display a listing of the PageDetail.
     */
    public function index($id, PageDetailDataTable $pageDetailDataTable)
    {
        $page = Page::find($id);

        if (empty($page)) {
            Flash::error(__('cmsadmin::models/pages.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.pages.index'));
        }

        return $pageDetailDataTable->render('cmsadmin::page_details.index', ['page' => $page]);
    }
}
