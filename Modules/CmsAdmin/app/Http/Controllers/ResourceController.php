<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\ResourceDataTable;
use Modules\CmsAdmin\app\DataTables\ResourceTrashDataTable;
use Modules\CmsAdmin\app\Models\Resource;
use Modules\CmsAdmin\app\Repositories\ResourceRepository;
use Modules\Common\app\Components\FileStorageManager;
use Modules\Common\app\Components\FileUploadManager;
use Modules\Common\app\Http\Controllers\BackendController;

class ResourceController extends BackendController
{
    /** @var ResourceRepository */
    private $resourceRepository;

    private $fileUploadPath;

    public function __construct(ResourceRepository $resourceRepo)
    {
        $this->moduleName = 'cmsadmin.resources';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
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

        $this->repository = $resourceRepo;
        $this->langFile = 'cmsadmin::models/resources';
        // define route for redirect
        $this->fileUploadPath = storage_path(RESOURCE_FILE_PATH);
        $this->listRoute = route('cmsadmin.resources.index');
        $this->trashListRoute = route('cmsadmin.resources.trashList');
        $this->detailRouteName = 'cmsadmin.resources.show';
    }

    /**
     * Display a listing of the Resource.
     */
    public function index(ResourceDataTable $resourceDataTable)
    {
        return $resourceDataTable->render('cmsadmin::resources.index');
    }

    /**
     * Display a listing of the Trashed Resources.
     */
    public function trashList(ResourceTrashDataTable $resourceTrashDataTable)
    {
        return $resourceTrashDataTable->render('cmsadmin::resources.trash');
    }

    /**
     * Show the form for creating a new Resource.
     */
    public function create()
    {
        return view('cmsadmin::resources.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Resource in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $resource = $this->repository->create($input);

        // manage files
        if ($request->has('file_name')) {
            $file = $request->input('file_name');
            if (!empty($file)) {
                $this->__manageFile($file, $resource, 'file_name');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.resources.show', ['resource' => $resource->resource_id]));
    }

    /**
     * Display the specified Resource.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);

        if ($mode == 'trash-restore') {
            if (!$resource = Resource::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($resource->trashed()) {
                return view('cmsadmin::resources.show-trash')
                    ->with('resource', $resource)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.resources.show', ['resource' => $resource->resource_id]));
            }
        } else {
            if (!$resource = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::resources.show')
            ->with('resource', $resource)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Resource.
     */
    public function edit($id)
    {
        if (!$resource = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $resource->file_name_pre = $resource->file_name;

        return view('cmsadmin::resources.edit')
            ->with('id', $resource->resource_id)
            ->with('publish', getOldData('publish', $resource->publish))
            ->with('reserved', getOldData('reserved', $resource->reserved))
            ->with('resource', $resource);
    }

    /**
     * Update the specified Resource in storage.
     */
    public function update($id, Request $request)
    {
        if (!$resource = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $resource->resource_id);
        $input = $request->all();

        $resource = $this->repository->update($input, $id);

        // manage files
        if ($request->has('file_name')) {
            $file = $request->input('file_name');
            $fileNamePre = $request->file_name_pre;
            if (!empty($file) && $fileNamePre != $file) {
                $this->__manageFile($file, $resource, 'file_name');
                // delete old file
                $this->__deleteFile($fileNamePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.resources.show', $id));
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'display_name' => removeString($request->get('display_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'file_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:100|unique:cms_resource,display_name',
            'file_size' => 'nullable|string|max:20',
            'file_type' => 'nullable|string|max:20',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __('cmsadmin::models/resources.fields');
        if (!empty($id)) {
            $rules['display_name'] = $rules['display_name'] . ',' . $id . ',resource_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    // manage file
    private function __manageFile($file = null, $model = null, $attribute = null)
    {
        if (!empty($file) && !empty($model)) {
            $processedFileData = FileUploadManager::processUploadedFile($file, $this->fileUploadPath);
            $fileName = isset($processedFileData['name']) ? $processedFileData['name'] : null;
            $fileType = isset($processedFileData['type']) ? $processedFileData['type'] : null;
            $fileSize = isset($processedFileData['size']) ? $processedFileData['size'] : null;

            if (!empty($fileName)) {
                $model->timestamps = false;
                $model->updateQuietly([
                    $attribute => $fileName,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                ]);
            }
        }
    }

    // delete file
    private function __deleteFile($file = null)
    {
        if (!empty($file)) {
            FileStorageManager::deleteFile($file, $this->fileUploadPath);
        }
    }
}
