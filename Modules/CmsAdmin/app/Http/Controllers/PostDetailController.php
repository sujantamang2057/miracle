<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Modules\CmsAdmin\app\DataTables\PostDetailDataTable;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Repositories\PostDetailRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class PostDetailController extends BackendController
{
    public function __construct(PostDetailRepository $postDetailRepositoryRepo)
    {
        $this->moduleName = 'cmsadmin.postDetails';

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

        $this->repository = $postDetailRepositoryRepo;
        $this->langFile = 'cmsadmin::models/posts.post_multidata';
        $id = request()->route('post');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.postDetails.index', ['post' => $id]);
    }

    /**
     * Display a listing of the postDetail.
     */
    public function index($id, PostDetailDataTable $postDetailDataTable)
    {
        $post = Post::find($id);

        if (empty($post)) {
            Flash::error(__('cmsadmin::models/posts.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.posts.index'));
        }

        return $postDetailDataTable->render('cmsadmin::post_details.index', ['post' => $post]);
    }
}
