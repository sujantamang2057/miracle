<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\PostCategoryDataTable;
use Modules\CmsAdmin\app\DataTables\PostCategoryTrashDataTable;
use Modules\CmsAdmin\app\Models\PostCategory;
use Modules\CmsAdmin\app\Repositories\PostCategoryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class PostCategoryController extends BackendController
{
    public function __construct(PostCategoryRepository $postCategoryRepo)
    {
        $this->moduleName = 'cmsadmin.postCategories';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'restore', 'togglePublish', 'toggleReserved', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
        ];

        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/post_categories';
        $this->repository = $postCategoryRepo;
        // define route for redirect
        $this->listRoute = route('cmsadmin.postCategories.index');
        $this->detailRouteName = 'cmsadmin.postCategories.show';
        $this->trashListRoute = route('cmsadmin.postCategories.trashList');

        // image PATH
        $this->imageFilePath = storage_path(POST_CATEGORY_FILE_PATH);
        // image DIMENSION
        $this->imageDimensions = json_decode(POST_CATEGORY_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the PostCategory.
     */
    public function index(PostCategoryDataTable $postCategoryDataTable)
    {
        return $postCategoryDataTable->render('cmsadmin::post_categories.index');
    }

    public function trashList(PostCategoryTrashDataTable $postCategoryTrashDataTable)
    {
        return $postCategoryTrashDataTable->render('cmsadmin::post_categories.trash');
    }

    /**
     * Show the form for creating a new PostCategory.
     */
    public function create()
    {
        $postCategory = new PostCategory;

        return view('cmsadmin::post_categories.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'))
            ->with('postCategory', $postCategory);

    }

    /**
     * Store a newly created PostCategory in storage.
     */
    public function store(Request $request)
    {
        $request = $this->__sanitize($request);
        $request['parent_category_id'] = $request['PostCategory']['parent_category_id'];

        $this->__validate($request);

        $postCategory = $this->repository->create($request->all());

        // manage images
        if ($request->has('category_image')) {
            $file = $request->category_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $postCategory, 'category_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.postCategories.show', ['post_category' => $postCategory->category_id]));
    }

    /**
     * Display the specified PostCategory.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        $parentCategories = PostCategory::where('category_id', '!=', $id)->get();

        if ($mode == 'trash-restore') {
            if (!$postCategory = PostCategory::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($postCategory->trashed()) {
                return view('cmsadmin::post_categories.show-trash')
                    ->with('postCategory', $postCategory)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.postCategories.show', ['postCategory' => $postCategory->category_id]));
            }
        } else {
            if (!$postCategory = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::post_categories.show')
            ->with('parentCategories', $parentCategories)
            ->with('postCategory', $postCategory)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified PostCategory.
     */
    public function edit($id)
    {
        if (!$postCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $postCategory->category_image_pre = $postCategory->category_image;

        return view('cmsadmin::post_categories.edit', compact('postCategory'))
            ->with('id', $postCategory->category_id)
            ->with('publish', getOldData('publish', $postCategory->publish))
            ->with('reserved', getOldData('reserved', $postCategory->reserved));
    }

    /**
     * Update the specified PostCategory in storage.
     */
    public function update($id, Request $request)
    {
        if (!$postCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $request['parent_category_id'] = $request['PostCategory']['parent_category_id'];
        $this->__validate($request, $postCategory->category_id);

        $postCategory = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has('category_image')) {
            $file = $request->category_image;
            $categoryImagePre = $request->category_image_pre;
            if (!empty($file) && $categoryImagePre != $file) {
                $this->__manageImageFile($file, $postCategory, 'category_image');
                // delete old image
                $this->__deleteImageFile($categoryImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.postCategories.show', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$postCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::post_categories.image_edit')
            ->with('postCategory', $postCategory)
            ->with('field', $field)
            ->with('id', $postCategory->category_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$postCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $postCategory = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $postCategory, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.postCategories.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = PostCategory::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    public function __sanitize($request)
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
        $rules = PostCategory::$rules;
        $messages = __('common::validation');

        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['category_name'] = $rules['category_name'] . ',' . $id . ',category_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',category_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    /**
     * Remove the specified PostCategory from storage.
     *
     * @throws \Exception
     */
}
