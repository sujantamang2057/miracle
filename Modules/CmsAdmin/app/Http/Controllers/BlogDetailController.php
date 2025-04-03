<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Modules\CmsAdmin\app\DataTables\BlogDetailDataTable;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Repositories\BlogDetailRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class BlogDetailController extends BackendController
{
    public function __construct(BlogDetailRepository $BlogDetailRepository)
    {
        $this->moduleName = 'cmsadmin.blogDetails';

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

        $this->repository = $BlogDetailRepository;
        $this->langFile = 'cmsadmin::models/blogs.blog_multidata';
        $id = request()->route('blog');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.blogDetails.index', ['blog' => $id]);
    }

    public function index($id, BlogDetailDataTable $blogDetailDataTable)
    {
        $blog = Blog::find($id);

        if (empty($blog)) {
            Flash::error(__('cmsadmin::models/blogs.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.blogs.index'));
        }

        return $blogDetailDataTable->render('cmsadmin::blog_details.index', ['blog' => $blog]);
    }
}
