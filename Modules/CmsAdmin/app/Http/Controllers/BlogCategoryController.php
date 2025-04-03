<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\BlogCategoryDataTable;
use Modules\CmsAdmin\app\DataTables\BlogCategoryTrashDataTable;
use Modules\CmsAdmin\app\Models\BlogCategory;
use Modules\CmsAdmin\app\Repositories\BlogCategoryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class BlogCategoryController extends BackendController
{
    public function __construct(BlogCategoryRepository $blogCategoryRepo)
    {
        $this->moduleName = 'cmsadmin.blogCategories';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy',  'togglePublish', 'toggleReserved', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $blogCategoryRepo;
        $this->langFile = 'cmsadmin::models/blog_categories';
        $this->listRoute = route('cmsadmin.blogCategories.index');
        $this->trashListRoute = route('cmsadmin.blogCategories.trashList');
        $this->detailRouteName = 'cmsadmin.blogCategories.show';
        $this->imageFilePath = storage_path(BLOG_CATEGORY_FILE_PATH);
        $this->imageDimensions = json_decode(BLOG_CATEGORY_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the BlogCategory.
     */
    public function index(BlogCategoryDataTable $blogCategoryDataTable)
    {
        return $blogCategoryDataTable->render('cmsadmin::blog_categories.index');
    }

    public function trashList(BlogCategoryTrashDataTable $blogCategoryTrashDataTable)
    {
        return $blogCategoryTrashDataTable->render('cmsadmin::blog_categories.trash');
    }

    public function create()
    {
        return view('cmsadmin::blog_categories.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created BlogCategory in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $blogCategory = $this->repository->create($input);

        // manage image
        if ($request->has('cat_image')) {
            $file = $request->cat_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $blogCategory, 'cat_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __('cmsadmin::models/blog_categories.singular')]))->important();

        return redirect(route('cmsadmin.blogCategories.show', ['blog_category' => $blogCategory->cat_id]));
    }

    /**
     * Display the specified BlogCategory.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$blogCategory = BlogCategory::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($blogCategory->trashed()) {
                return view('cmsadmin::blog_categories.show-trash')
                    ->with('blogCategory', $blogCategory)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.blogCategories.show', ['blogCategory' => $blogCategory->cat_id]));
            }
        } else {
            if (!$blogCategory = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::blog_categories.show')
            ->with('blogCategory', $blogCategory)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified BlogCategory.
     */
    public function edit($id)
    {
        if (!$blogCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $blogCategory->cat_image_pre = $blogCategory->cat_image;

        return view('cmsadmin::blog_categories.edit')
            ->with('blogCategory', $blogCategory)
            ->with('id', $blogCategory->cat_id)
            ->with('publish', getOldData('publish', $blogCategory->publish))
            ->with('reserved', getOldData('reserved', $blogCategory->reserved));
    }

    /**
     * Update the specified BlogCategory in storage.
     */
    public function update($id, Request $request)
    {
        if (!$blogCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request, $blogCategory->cat_id);

        $input = $request->all();

        $blogCategory = $this->repository->update($input, $id);

        // manage images
        if ($request->has('cat_image')) {
            $file = $request->cat_image;
            $blogCatImagePre = $request->cat_image_pre;
            if (!empty($file) && $blogCatImagePre != $file) {
                $this->__manageImageFile($file, $blogCategory, 'cat_image');
                // delete old image
                $this->__deleteImageFile($blogCatImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __('cmsadmin::models/blog_categories.singular')]))->important();

        return redirect(route('cmsadmin.blogCategories.show', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$blogCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::blog_categories.image_edit')
            ->with('blogCategory', $blogCategory)
            ->with('field', $field)
            ->with('id', $blogCategory->cat_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$blogCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $blogCategory = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $blogCategory, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.blogCategories.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = BlogCategory::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'cat_title' => removeString($request->get('cat_title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    // validation rules
    public function __validate($request, $id = null)
    {
        $rules = [
            'cat_title' => 'required|string|max:191|unique:cms_blog_category,cat_title',
            'cat_slug' => 'nullable|string|max:191|unique:cms_blog_category,cat_slug',
            'cat_image' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:65535',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __('cmsadmin::models/blog_categories.fields');
        if (!empty($id)) {
            $rules['cat_title'] = $rules['cat_title'] . ',' . $id . ',cat_id';
            $rules['cat_slug'] = $rules['cat_slug'] . ',' . $id . ',cat_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }
}
