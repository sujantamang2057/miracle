<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\MenuDataTable;
use Modules\CmsAdmin\app\DataTables\MenuTrashDataTable;
use Modules\CmsAdmin\app\Models\Menu;
use Modules\CmsAdmin\app\Repositories\MenuRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class MenuController extends BackendController
{
    /** @var MenuRepository */
    public function __construct(MenuRepository $menuRepo)
    {
        $this->moduleName = 'cmsadmin.menus';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'toggleActive', 'toggleReserved', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['toggleActive']) => ['toggleActive'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $menuRepo;
        $this->langFile = 'cmsadmin::models/menus';
        // define route for redirect
        $this->listRoute = route('cmsadmin.menus.index');
        $this->trashListRoute = route('cmsadmin.menus.trashList');
        $this->detailRouteName = 'cmsadmin.menus.show';
    }

    /**
     * Display a listing of the Menu.
     */
    public function index(MenuDataTable $menuDataTable)
    {
        return $menuDataTable->render('cmsadmin::menus.index');
    }

    /**
     * Display a listing of the Trashed Menu.
     */
    public function trashList(MenuTrashDataTable $menuTrashDataTable)
    {
        return $menuTrashDataTable->render('cmsadmin::menus.trash');
    }

    /**
     * Show the form for creating a new Menu.
     */
    public function create()
    {
        $parentMenuList = Menu::getParentLists(null, true);

        return view('cmsadmin::menus.create')
            ->with('parentMenuList', $parentMenuList)
            ->with('id', null)
            ->with('url_type', getOldData('url_type', 1))
            ->with('url_target', getOldData('url_target', 1))
            ->with('active', getOldData('active'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Menu in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $menu = $this->repository->create($request->all());

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.menus.show', ['menu' => $menu->menu_id]));
    }

    /**
     * Display the specified Menu.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$menu = Menu::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($menu->trashed()) {
                return view('cmsadmin::menus.show-trash')
                    ->with('menu', $menu)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.menus.show', ['menu' => $menu->menu_id]));
            }
        } else {
            if (!$menu = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::menus.show')
            ->with('menu', $menu)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Menu.
     */
    public function edit($id)
    {
        if (!$menu = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $parentMenuList = Menu::getParentLists($id, true);

        return view('cmsadmin::menus.edit', compact('menu', 'parentMenuList'))
            ->with('id', $menu->menu_id)
            ->with('url_type', getOldData('url_type', $menu->url_type))
            ->with('url_target', getOldData('url_target', $menu->url_target))
            ->with('active', getOldData('active', $menu->active))
            ->with('reserved', getOldData('reserved', $menu->reserved));
    }

    /**
     * Update the specified Menu in storage.
     */
    public function update($id, Request $request)
    {
        if (!$menu = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $menu->menu_id);
        $input = $request->all();

        $menu = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.menus.show', $id));
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $url_type = ($request->get('url_type') == 'on') ? 1 : 2;
        $url_target = ($request->get('url_target') == 'on') ? 1 : 2;
        $active = ($request->get('active') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'url_type' => $url_type,
            'url_target' => $url_target,
            'active' => $active,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    // validation rules
    public function __validate($request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:191|unique:cms_menu,title',
            'slug' => 'nullable|string|max:191|unique:cms_menu,slug',
            'parent_id' => 'nullable|exists:cms_menu,menu_id',
            'url' => ['required', 'string', 'max:255'],
            'url_type' => 'required',
            'url_target' => 'required',
            'css_class' => 'nullable|string|max:255',
            'tooltip' => 'nullable|string|max:255',
            'active' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['title'] = $rules['title'] . ',' . $id . ',menu_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',menu_id';
        }
        if ($request->url_type == 1) {
            $rules['url'][] = new \Modules\Common\app\Rules\InternalUrl;
        } else {
            $rules['url'][] = 'url';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    // toggle active
    public function toggleActive(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->__setActive($id);
                }

                return response()->json([
                    'msgType' => 'success',
                    'msg' => __('common::messages.active_toggle', ['model' => __($this->langFile . '.plural')]),
                ]);
            } else {
                if ($this->__setActive($selection)) {
                    return response()->json([
                        'msgType' => 'success',
                        'msg' => __('common::messages.active_toggle', ['model' => __($this->langFile . '.singular')]),
                    ]);
                }
            }
        }

        return response()->json(['message' => __($this->langFile . '.toggle_active_failed')]);
    }

    private function __setActive($id)
    {
        $model = $this->repository->find($id);
        if (!empty($model)) {
            $active = ($model->active == 2) ? 1 : 2;
            $model->update([
                'active' => $active,
            ]);

            return true;
        }

        return false;
    }
}
