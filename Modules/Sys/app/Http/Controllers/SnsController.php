<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\DataTables\SnsDataTable;
use Modules\Sys\app\Models\Sns;
use Modules\Sys\app\Repositories\SnsRepository;

class SnsController extends BackendController
{
    /** @var SnsRepository */
    private $snsRepository;

    public function __construct(SnsRepository $snsRepository)
    {
        $this->moduleName = 'sys.sns';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageUpdate', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $snsRepository;
        $this->langFile = 'sys::models/sns';
        // redirect route page
        $this->listRoute = route('sys.sns.index');
        // image path
        $this->imageFilePath = storage_path(SNS_FILE_PATH);
        // image Dimension
        $this->imageDimensions = json_decode(SNS_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Sns.
     */
    public function index(SnsDataTable $snsDataTable)
    {
        return $snsDataTable->render('sys::sns.index');
    }

    /**
     * Show the form for creating a new Sns.
     */
    public function create()
    {
        return view('sys::sns.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Sns in storage.
     */
    public function store(Request $request)
    {
        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $sns = $this->repository->create($input);

        // manage icon file
        if ($request->has('icon')) {
            $file = $request->icon;
            if (!empty($file)) {
                $this->__manageImageFile($file, $sns, 'icon');
            }

        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.sns.show', $sns->sns_id));
    }

    /**
     * Display the specified Sns.
     */
    public function show($id)
    {
        if (!$sns = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('sys::sns.show')->with('sns', $sns);
    }

    /**
     * Show the form for editing the specified Sns.
     */
    public function edit($id)
    {
        if (!$sns = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $sns->icon_pre = $sns->icon;

        return view('sys::sns.edit')->with('sns', $sns)
            ->with('id', $sns->sns_id)
            ->with('publish', getOldData('publish', $sns->publish))
            ->with('reserved', getOldData('reserved', $sns->reserved));
    }

    /**
     * Update the specified Sns in storage.
     */
    public function update($id, Request $request)
    {
        if (!$sns = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $sns->sns_id);

        $sns = $this->repository->update($request->all(), $id);

        // manage icons
        if ($request->has('icon')) {
            $file = $request->icon;
            $iconPre = $request->icon_pre;
            if (!empty($file) && $iconPre != $file) {
                $this->__manageImageFile($file, $sns, 'icon');
                // delete old icon
                $this->__deleteImageFile($iconPre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.sns.show', $id));
    }

    public function imageEdit($id, $field)
    {
        if (!$sns = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('sys::sns.image_edit')
            ->with('sns', $sns)
            ->with('field', $field)
            ->with('id', $sns->sns_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$sns = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $sns = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $sns, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.icon')]))->important();

        return redirect(route('sys.sns.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = Sns::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // sanitize inout
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    // validate rules
    private function __validate($request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:100|unique:sys_sns,title',
            'icon' => 'nullable|string|max:100|required_without:class',
            'class' => 'nullable|string|max:50',
            'url' => 'required|string|max:255|url',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages1 = __('common::validation');
        $messages2 = [
            'icon.required_without' => __($this->langFile . '.messages.required_without'),
        ];
        $messages = $messages1 + $messages2;
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['title'] = $rules['title'] . ',' . $id . ',sns_id';

        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
