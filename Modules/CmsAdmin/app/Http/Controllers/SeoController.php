<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\SeoDataTable;
use Modules\CmsAdmin\app\DataTables\SeoTrashDataTable;
use Modules\CmsAdmin\app\Models\Seo;
use Modules\CmsAdmin\app\Repositories\SeoRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class SeoController extends BackendController
{
    public function __construct(SeoRepository $seoRepo)
    {
        $this->moduleName = 'cmsadmin.seos';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],

        ];

        applyMiddleware($this, $middlewareMap);

        $this->repository = $seoRepo;
        $this->langFile = 'cmsadmin::models/seos';
        // define route for redirect
        $this->listRoute = route('cmsadmin.seos.index');
        $this->trashListRoute = route('cmsadmin.seos.trashList');
        $this->detailRouteName = 'cmsadmin.seos.show';
    }

    /**
     * Display a listing of the Seo.
     */
    public function index(SeoDataTable $seoDataTable)
    {
        return $seoDataTable->render('cmsadmin::seos.index');
    }

    /**
     * Display a listing of the Trashed Seo.
     */
    public function trashList(SeoTrashDataTable $seoTrashDataTable)
    {
        return $seoTrashDataTable->render('cmsadmin::seos.trash');
    }

    /**
     * Show the form for creating a new Seo.
     */
    public function create()
    {
        return view('cmsadmin::seos.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'));
    }

    /**
     * Store a newly created Seo in storage.
     */
    public function store(Request $request)
    {
        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $seo = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.seos.show', ['seo' => $seo->id]));
    }

    /**
     * Display the specified Seo.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$seo = Seo::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($seo->trashed()) {
                return view('cmsadmin::seos.show-trash')
                    ->with('seo', $seo)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.seos.show', ['seo' => $seo->id]));
            }
        } else {
            if (!$seo = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::seos.show')
            ->with('seo', $seo)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Seo.
     */
    public function edit($id)
    {
        if (!$seo = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::seos.edit')
            ->with('id', $seo->seo_id)
            ->with('seo', $seo)
            ->with('publish', getOldData('publish', $seo->publish));
    }

    /**
     * Update the specified Seo in storage.
     */
    public function update($id, Request $request)
    {
        if (!$seo = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $seo->id);

        $input = $request->all();
        $seo = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.seos.show', $id));
    }

    // sanitize  input
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $request->merge([
            'module_name' => removeString($request->get('module_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'module_name' => 'required|string|max:50',
            'code' => 'required|string|max:25|regex:' . PREG_SEO_CODE . '|unique:seo_metadata,code',
            'title' => 'required|string|max:255',
            'title_locale' => 'required|string|max:255',
            'keyword' => 'required|string|max:255',
            'keyword_locale' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'description_locale' => 'nullable|string|max:255',
            'publish' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation') + __($this->langFile . '.validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['code'] = $rules['code'] . ',' . $id . ',id';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
