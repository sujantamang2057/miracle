<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\BannerDataTable;
use Modules\CmsAdmin\app\DataTables\BannerTrashDataTable;
use Modules\CmsAdmin\app\Models\Banner;
use Modules\CmsAdmin\app\Repositories\BannerRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class BannerController extends BackendController
{
    public function __construct(BannerRepository $bannerRepo)
    {
        $this->moduleName = 'cmsadmin.banners';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList', 'imageEdit']) => ['index'],
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

        $this->repository = $bannerRepo;
        $this->langFile = 'cmsadmin::models/banners';
        $this->listRoute = route('cmsadmin.banners.index');
        $this->trashListRoute = route('cmsadmin.banners.trashList');
        $this->detailRouteName = 'cmsadmin.banners.show';
        $this->imageFilePath = storage_path(BANNER_FILE_PATH);
        $this->imageDimensions = json_decode(BANNER_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Banner.
     */
    public function index(BannerDataTable $bannerDataTable)
    {
        return $bannerDataTable->render('cmsadmin::banners.index');
    }

    /**
     * Display a listing of the trashed Banner.
     */
    public function trashList(BannerTrashDataTable $bannertrashDataTable)
    {
        return $bannertrashDataTable->render('cmsadmin::banners.trash');
    }

    /**
     * Show the form for creating a new Banner.
     */
    public function create()
    {
        return view('cmsadmin::banners.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'))
            ->with('url_target', 1);
    }

    /**
     * Store a newly created Banner in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $banner = $this->repository->create($request->all());

        // manage images
        if ($request->has('pc_image')) {
            $file = $request->pc_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $banner, 'pc_image');
            }
        }

        if ($request->has('sp_image')) {
            $file = $request->sp_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $banner, 'sp_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __('cmsadmin::models/banners.singular')]))->important();

        return redirect(route('cmsadmin.banners.show', ['banner' => $banner->banner_id]));
    }

    /**
     * Display the specified Banner.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$banner = Banner::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($banner->trashed()) {
                return view('cmsadmin::banners.show-trash')
                    ->with('banner', $banner)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.banners.show', ['banner' => $banner->banner_id]));
            }
        } else {
            if (!$banner = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::banners.show')
            ->with('banner', $banner)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Banner.
     */
    public function edit($id)
    {
        if (!$banner = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $banner->pc_image_pre = $banner->pc_image;
        $banner->sp_image_pre = $banner->sp_image;

        return view('cmsadmin::banners.edit')
            ->with('id', $banner->banner_id)
            ->with('publish', getOldData('publish', $banner->publish))
            ->with('reserved', getOldData('reserved', $banner->reserved))
            ->with('url_target', $banner->url_target)
            ->with('banner', $banner);
    }

    /**
     * Update the specified Banner in storage.
     */
    public function update($id, Request $request)
    {
        if (!$banner = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $banner->banner_id);

        $banner = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has('pc_image')) {
            $file = $request->pc_image;
            $pcImagePre = $request->pc_image_pre;
            if (!empty($file) && $pcImagePre != $file) {
                $this->__manageImageFile($file, $banner, 'pc_image');
                // delete old image
                $this->__deleteImageFile($pcImagePre);
            }
        }

        if ($request->has('sp_image')) {
            $file = $request->sp_image;
            $spImagePre = $request->sp_image_pre;
            if (!empty($file) && $spImagePre != $file) {
                $this->__manageImageFile($file, $banner, 'sp_image');
                // delete old image
                $this->__deleteImageFile($spImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __('cmsadmin::models/banners.singular')]))->important();

        return redirect(route('cmsadmin.banners.show', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$banner = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::banners.image_edit')
            ->with('banner', $banner)
            ->with('field', $field)
            ->with('id', $banner->banner_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$banner = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $banner = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $banner, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.banners.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = [
            'pc_image' => 'required|string|max:100',
            'sp_image' => 'nullable|string|max:100',
        ];
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $url_target = ($request->get('url_target') == 'on') ? 1 : 2;
        $request->merge([
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
            'url_target' => $url_target,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:191|unique:cms_banner,title',
            'description' => 'nullable|string|max:300',
            'pc_image' => 'required|string|max:100',
            'sp_image' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:255',
            'url_target' => 'required|integer|min:1|max:2',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __('cmsadmin::models/banners.fields');
        if (!empty($id)) {
            $rules['title'] = $rules['title'] . ',' . $id . ',banner_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }
}
