<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use DB;
use Flash;
use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\PageHelper;
use Modules\CmsAdmin\app\DataTables\PageDataTable;
use Modules\CmsAdmin\app\DataTables\PageTrashDataTable;
use Modules\CmsAdmin\app\Models\Page;
use Modules\CmsAdmin\app\Models\PageDetail;
use Modules\CmsAdmin\app\Repositories\PageRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class PageController extends BackendController
{
    public function __construct(PageRepository $pageRepo)
    {
        $this->moduleName = 'cmsadmin.pages';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'multidata', 'saveMultidata', 'togglePublish', 'toggleReserved', 'regenerate', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['multidata']) => ['multidata'],
            buildCanMiddleware($this->moduleName, ['saveMultidata']) => ['saveMultidata'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['regenerate']) => ['regenerate'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['clone']) => ['clone'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/pages';
        $this->commonLangFile = 'common::crud';
        $this->repository = $pageRepo;
        // define route for redirect
        $this->listRoute = route('cmsadmin.pages.index');
        $this->trashListRoute = route('cmsadmin.pages.trashList');
        $this->detailRouteName = 'cmsadmin.pages.show';
        // Define PATH
        $this->imageFilePath = storage_path(PAGE_FILE_PATH);
        // Define DIMENSION
        $this->imageDimensions = json_decode(PAGE_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the page.
     */
    public function index(PageDataTable $pageDataTable)
    {
        return $pageDataTable->render('cmsadmin::pages.index');
    }

    /**
     * Display a listing of the Trashed page.
     */
    public function trashList(PageTrashDataTable $pageTrashDataTable)
    {
        return $pageTrashDataTable->render('cmsadmin::pages.trash');
    }

    /**
     * Show the form for creating a new Page.
     */
    public function create()
    {
        return view('cmsadmin::pages.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Page in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $page = $this->repository->create($request->all());
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $page, 'banner_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.pages.show', ['page' => $page->page_id]));
    }

    /**
     * Display the specified Page.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$page = Page::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($page->trashed()) {
                return view('cmsadmin::pages.show-trash')
                    ->with('page', $page)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.pages.show', ['page' => $page->page_id]));
            }
        } else {
            if (!$page = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::pages.show')
            ->with('page', $page)
            ->with('mode', $mode);
    }

    /**
     * Show the form for cloning the specified Page.
     */
    public function clone($id)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $page = $this->__loadCloneData($page, 'banner_image');

        return view('cmsadmin::pages.clone')
            ->with('id', null)
            ->with('publish', getOldData('publish', $page->publish))
            ->with('reserved', getOldData('reserved', $page->reserved))
            ->with('page', $page);
    }

    /**
     * Show the form for editing the specified Page.
     */
    public function edit($id)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $page->banner_image_pre = $page->banner_image;

        return view('cmsadmin::pages.edit')
            ->with('id', $page->page_id)
            ->with('publish', getOldData('publish', $page->publish))
            ->with('reserved', getOldData('reserved', $page->reserved))
            ->with('page', $page);
    }

    /**
     * Update the specified Page in storage.
     */
    public function update($id, Request $request)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);

        // validation
        $this->__validate($request, $id);
        $page = $this->repository->update($request->all(), $id);
        // manage images
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            $bannerImagePre = $request->banner_image_pre;
            if (!empty($file) && $bannerImagePre != $file) {
                $this->__manageImageFile($file, $page, 'banner_image');
                // delete old image
                $this->__deleteImageFile($bannerImagePre);
            }
        }
        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.pages.show', $id));
    }

    public function regenerate(Request $request)
    {
        $selection = $request->id;

        if (!empty($selection)) {
            $selection = is_array($selection) ? $selection : [$selection];

            $isStatic = false;
            $isDynamic = false;

            foreach ($selection as $id) {
                if ($this->__isStatic($id)) {
                    $isStatic = true;
                } else {
                    $isDynamic = true;
                }
            }

            if ($isStatic && !$isDynamic) {
                return response()->json([
                    'msg' => __('common::messages.regenerate_error_static', ['model' => __($this->langFile . '.singular')]),
                    'msgType' => 'danger',
                ]);
            } else {
                foreach ($selection as $id) {
                    if (!$this->__isStatic($id)) {
                        $this->repository->find($id)->save();
                    }
                }

                return response()->json([
                    'msg' => __('common::messages.regenerated', ['model' => __($this->langFile . '.plural')]),
                    'msgType' => 'success',
                ]);

                return redirect($this->listRoute);
            }
        }

        Flash::error(__('common::messages.regenerate_error', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect($this->listRoute);
    }

    public function multidata($id)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $pageDetails = $page->details;
        $multidata = [];
        if (!empty($pageDetails)) {
            foreach ($pageDetails as $key => $detail) {
                $multidata[$key]['detail_id'] = $detail->detail_id;
                $multidata[$key]['title'] = $detail->title;
                $multidata[$key]['layout'] = $detail->layout;
                $multidata[$key]['image_pre'] = $multidata[$key]['image'] = $detail->image;
                $multidata[$key]['detail'] = $detail->detail;
                $multidata[$key]['publish'] = $detail->publish;
            }
        }
        $oldMultidata = old('multidata');
        if (!empty($oldMultidata)) {
            foreach ($oldMultidata as $key => $old) {
                $multidata[$key]['title'] = isset($old['title']) ? $old['title'] : null;
                $multidata[$key]['layout'] = isset($old['layout']) ? $old['layout'] : 1;
                $multidata[$key]['image'] = isset($old['image']) ? $old['image'] : null;
                $multidata[$key]['detail'] = isset($old['detail']) ? $old['detail'] : null;
            }
        }

        return view('cmsadmin::pages.multidata')
            ->with('page', $page)
            ->with('multidata', $multidata);
    }

    public function saveMultiData($id, Request $request)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $pageDetails = $page->details;
        $rules = [
            'multidata.*.title' => 'required|string|max:255',
            'multidata.*.image' => 'nullable|string|max:255',
            'multidata.*.detail' => 'nullable|string|max:65535',
            'multidata.*.layout' => 'nullable|integer|min:1|max:4',
        ];
        $this->validate($request, $rules, ['required' => __('common::messages.is_required', ['field' => __('common::multidata.fields.title')])]);
        $multidata = $request->input('multidata');
        $oldItems = !empty($pageDetails) ? $pageDetails->pluck('detail_id')->toArray() : [];
        $safeItems = [];

        if (!empty($multidata)) {
            foreach ($multidata as $key => $data) {
                $detailId = isset($data['detail_id']) ? $data['detail_id'] : null;
                if (!empty($detailId) && in_array($detailId, $oldItems)) {
                    $safeItems[] = $data['detail_id'];
                }
                $image = isset($data['image']) ? $data['image'] : null;
                $imagePre = isset($data['image_pre']) ? $data['image_pre'] : null;
                unset($data['image_pre']);
                // save or update page detail
                $currentPageDetail = $page->details()->updateOrCreate(['detail_id' => $detailId], $data);

                if (!empty($image) && $imagePre != $image) {
                    $this->__manageImageFile($image, $currentPageDetail, 'image');
                    // delete old image
                    $this->__deleteImageFile($imagePre);
                }
            }
        }
        $unsafeItems = array_diff($oldItems, $safeItems);
        PageDetail::find($unsafeItems)->each->delete();

        return redirect(route('cmsadmin.pageDetails.index', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::pages.image_edit')
            ->with('page', $page)
            ->with('field', $field)
            ->with('id', $page->page_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$page = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $page = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $page, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.pages.index'));
    }

    public function preview(Request $request)
    {

        $page_id = $request->route('id');
        $request->merge(['page_id' => $page_id]);
        if ($request->ajax()) {
            $request = $this->__sanitize($request);
            $validationErrors = $this->__validate($request, $page_id);
            if ($validationErrors) {
                return response()->json([
                    'success' => false,
                    'errors' => $validationErrors,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('common::validation.success'),
            ]);
        }
        try {
            $page = (object) $request->all();
            if (isset($page->page_type) == false) {
                $page_type = PageHelper::getPageTypeById($page_id);
                $page->page_type = $page_type;
            }
            $preview = 'true';

            return response()->view('cms::page.index', ['page' => $page, 'preview' => $preview], 200);
        } catch (\Exception $e) {
            Flash::error(__('common::messages.model_data_not_found', ['model' => __($this->langFile . '.singular')]))->important();

            return redirect($this->listRoute);
        }
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = Page::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'page_title' => removeString($request->get('page_title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'page_title' => 'required|string|max:255|unique:cms_page,page_title',
            'slug' => 'nullable|string|max:255|unique:cms_page,slug',
            'page_type' => 'required|integer|min:1|max:2',
            'page_file_name' => 'nullable|string|max:100',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
            'banner_image' => 'nullable|string|max:100',
            'published_date' => 'required|date',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes1 = __($this->langFile . '.fields');
        $attributes2 = __($this->commonLangFile . '.fields');
        $attributes = $attributes1 + $attributes2;
        if (!empty($id)) {
            unset($rules['page_type']);
            $rules['page_title'] = $rules['page_title'] . ',' . $id . ',page_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',page_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    private function __isStatic($ids)
    {
        $model = $this->repository->makeModel();

        if ($model->hasAttribute('page_type')) {
            $query = DB::table($model->getTable())
                ->where('page_type', 1);

            if (is_array($ids)) {
                $query->whereIn($model->getKeyName(), $ids);
            } else {
                $query->where($model->getKeyName(), $ids);
            }

            return $query->exists();
        }

        return false;
    }
}
