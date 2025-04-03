<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\FaqCategoryDataTable;
use Modules\CmsAdmin\app\DataTables\FaqCategoryTrashDataTable;
use Modules\CmsAdmin\app\Models\FaqCategory;
use Modules\CmsAdmin\app\Repositories\FaqCategoryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class FaqCategoryController extends BackendController
{
    public function __construct(FaqCategoryRepository $faqCategoryRepo)
    {
        $this->moduleName = 'cmsadmin.faqCategories';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList', 'reorder', 'imageEdit']) => ['index'],
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
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $faqCategoryRepo;
        $this->langFile = 'cmsadmin::models/faq_categories';
        // define route for redirect
        $this->listRoute = route('cmsadmin.faqCategories.index');
        $this->trashListRoute = route('cmsadmin.faqCategories.trashList');
        $this->detailRouteName = 'cmsadmin.faqCategories.show';
        // Define PATH
        $this->imageFilePath = storage_path(FAQ_CATEGORY_FILE_PATH);
        // Define DIMENSION
        $this->imageDimensions = json_decode(FAQ_CATEGORY_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the FaqCategory.
     */
    public function index(FaqCategoryDataTable $faqCategoryDataTable)
    {
        return $faqCategoryDataTable->render('cmsadmin::faq_categories.index');
    }

    /**
     * Display a listing of the Trashed FaqCategory.
     */
    public function trashList(FaqCategoryTrashDataTable $faqCategoryTrashDataTable)
    {
        return $faqCategoryTrashDataTable->render('cmsadmin::faq_categories.trash');
    }

    /**
     * Show the form for creating a new FaqCategory.
     */
    public function create()
    {
        return view('cmsadmin::faq_categories.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created FaqCategory in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $faqCategory = $this->repository->create($input);

        // manage image
        if ($request->has('faq_cat_image')) {
            $file = $request->faq_cat_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $faqCategory, 'faq_cat_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.faqCategories.show', ['faq_category' => $faqCategory->faq_cat_id]));
    }

    /**
     * Display the specified FaqCategory.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$faqCategory = FaqCategory::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($faqCategory->trashed()) {
                return view('cmsadmin::faq_categories.show-trash')
                    ->with('faqCategory', $faqCategory)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.faqCategories.show', ['faqCategory' => $faqCategory->faq_cat_id]));
            }
        } else {
            if (!$faqCategory = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::faq_categories.show')
            ->with('faqCategory', $faqCategory)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified FaqCategory.
     */
    public function edit($id)
    {
        if (!$faqCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $faqCategory->faq_cat_image_pre = $faqCategory->faq_cat_image;

        return view('cmsadmin::faq_categories.edit', compact('faqCategory'))
            ->with('id', $faqCategory->faq_cat_id)
            ->with('publish', getOldData('publish', $faqCategory->publish))
            ->with('reserved', getOldData('reserved', $faqCategory->reserved));
    }

    /**
     * Update the specified FaqCategory in storage.
     */
    public function update($id, Request $request)
    {
        if (!$faqCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $faqCategory->faq_cat_id);
        $input = $request->all();

        $faqCategory = $this->repository->update($input, $id);

        // manage images
        if ($request->has('faq_cat_image')) {
            $file = $request->faq_cat_image;
            $faqCatImagePre = $request->faq_cat_image_pre;
            if (!empty($file) && $faqCatImagePre != $file) {
                $this->__manageImageFile($file, $faqCategory, 'faq_cat_image');
                // delete old image
                $this->__deleteImageFile($faqCatImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.faqCategories.show', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$faqCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::faq_categories.image_edit')
            ->with('faqCategory', $faqCategory)
            ->with('field', $field)
            ->with('id', $faqCategory->faq_cat_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$faqCategory = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $faqCategory = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $faqCategory, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.faqCategories.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = FaqCategory::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'faq_cat_name' => removeString($request->get('faq_cat_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    // validation rules
    public function __validate($request, $id = null)
    {
        $rules = [
            'faq_cat_name' => 'required|string|max:191|unique:cms_faq_category,faq_cat_name',
            'slug' => 'nullable|string|max:191|unique:cms_faq_category,slug',
            'faq_cat_image' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:255',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['faq_cat_name'] = $rules['faq_cat_name'] . ',' . $id . ',faq_cat_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',faq_cat_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }
}
