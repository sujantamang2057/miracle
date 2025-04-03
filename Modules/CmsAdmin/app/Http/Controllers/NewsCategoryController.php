<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\NewsCategoryDataTable;
use Modules\CmsAdmin\app\DataTables\NewsCategoryTrashDataTable;
use Modules\CmsAdmin\app\Models\NewsCategory;
use Modules\CmsAdmin\app\Repositories\NewsCategoryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class NewsCategoryController extends BackendController
{
    public function __construct(NewsCategoryRepository $newsCategoryRepo)
    {
        $this->moduleName = 'cmsadmin.newsCategories';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList', 'reorder']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $newsCategoryRepo;
        $this->langFile = 'cmsadmin::models/news_categories';
        // define route for redirect
        $this->listRoute = route('cmsadmin.newsCategories.index');
        $this->detailRouteName = 'cmsadmin.newsCategories.show';
        $this->trashListRoute = route('cmsadmin.newsCategories.trashList');
    }

    /**
     * Display a listing of the NewsCategory.
     */
    public function index(NewsCategoryDataTable $newsCategoryDataTable)
    {
        return $newsCategoryDataTable->render('cmsadmin::news_categories.index');
    }

    /**
     * Display a listing of the Trashed NewsCategory.
     */
    public function trashList(NewsCategoryTrashDataTable $newsCategoryTrashDataTable)
    {
        return $newsCategoryTrashDataTable->render('cmsadmin::news_categories.trash');
    }

    /**
     * Show the form for creating a new NewsCategory.
     */
    public function create()
    {
        $newsCategory = new NewsCategory;
        $parentCategoriesList = NewsCategory::getParentLists(null, true);

        return view('cmsadmin::news_categories.create', compact('newsCategory', 'parentCategoriesList'))
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created NewsCategory in storage.
     */
    public function store(Request $request)
    {
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $input = $request->all();
        $newsCategory = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.newsCategories.show', ['news_category' => $newsCategory->category_id]));
    }

    /**
     * Display the specified NewsCategory.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        $parentCategories = NewsCategory::where('category_id', '!=', $id)->get();

        if ($mode == 'trash-restore') {
            if (!$newsCategory = NewsCategory::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($newsCategory->trashed()) {
                return view('cmsadmin::news_categories.show-trash')
                    ->with('newsCategory', $newsCategory)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.newsCategories.show', ['newsCategory' => $newsCategory->category_id]));
            }
        } else {
            if (!$newsCategory = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::news_categories.show')
            ->with('newsCategory', $newsCategory)
            ->with('parentCategories', $parentCategories)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified NewsCategory.
     */
    public function edit($id)
    {
        if (!$newsCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $parentCategoriesList = NewsCategory::getParentLists($id, true);

        return view('cmsadmin::news_categories.edit', compact('newsCategory', 'parentCategoriesList'))
            ->with('id', $newsCategory->category_id)
            ->with('publish', getOldData('publish', $newsCategory->publish))
            ->with('reserved', getOldData('reserved', $newsCategory->reserved));
    }

    /**
     * Update the specified NewsCategory in storage.
     */
    public function update($id, Request $request)
    {
        if (!$newsCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        // sanitize
        $request = $this->__sanitize($request);
        $this->__validate($request, $newsCategory->category_id);

        $input = $request->all();
        $newsCategory = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.newsCategories.show', $id));
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'category_name' => removeString($request->get('category_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'category_name' => 'required|string|max:150|unique:cms_news_category,category_name',
            'slug' => 'nullable|string|max:150|unique:cms_news_category,slug',
            'parent_category_id' => 'nullable|exists:cms_news_category,category_id',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];

        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['category_name'] = $rules['category_name'] . ',' . $id . ',category_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',category_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }
}
