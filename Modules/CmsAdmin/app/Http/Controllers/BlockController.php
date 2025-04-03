<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\BlockDataTable;
use Modules\CmsAdmin\app\DataTables\BlockTrashDataTable;
use Modules\CmsAdmin\app\Models\Block;
use Modules\CmsAdmin\app\Repositories\BlockRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class BlockController extends BackendController
{
    public function __construct(BlockRepository $blockRepo)
    {
        $this->moduleName = 'cmsadmin.blocks';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'regenerate', 'togglePublish', 'toggleReserved', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['regenerate']) => ['regenerate'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $blockRepo;
        $this->langFile = 'cmsadmin::models/blocks';

        // define route for redirect
        $this->listRoute = route($this->moduleName . '.index');
        $this->trashListRoute = route($this->moduleName . '.trashList');
        $this->detailRouteName = $this->moduleName . '.show';
    }

    /**
     * Display a listing of the Block.
     */
    public function index(BlockDataTable $blockDataTable)
    {
        return $blockDataTable->render('cmsadmin::blocks.index');
    }

    /**
     * Display a listing of the Trashed Block.
     */
    public function trashList(BlockTrashDataTable $blockTrashDataTable)
    {
        return $blockTrashDataTable->render('cmsadmin::blocks.trash');
    }

    /**
     * Show the form for creating a new Block.
     */
    public function create()
    {
        return view('cmsadmin::blocks.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Block in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $input = $request->all();
        $block = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.blocks.show', ['block' => $block->block_id]));
    }

    /**
     * Display the specified Block.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);

        if ($mode == 'trash-restore') {
            if (!$block = Block::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($block->trashed()) {
                return view('cmsadmin::blocks.show-trash')
                    ->with('block', $block)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.blocks.show', ['block' => $block->block_id]));
            }
        } else {
            if (!$block = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::blocks.show')
            ->with('block', $block)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Block.
     */
    public function edit($id)
    {
        if (!$block = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::blocks.edit')
            ->with('id', $block->block_id)
            ->with('publish', getOldData('publish', $block->publish))
            ->with('reserved', getOldData('reserved', $block->reserved))
            ->with('block', $block);
    }

    /**
     * Update the specified Block in storage.
     */
    public function update($id, Request $request)
    {
        if (!$block = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $block->block_id);

        $input = $request->all();
        $block = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.blocks.show', $id));
    }

    public function regenerate(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->repository->find($id)->save();
                }

                return response()->json([
                    'msgType' => 'success',
                    'msg' => __('common::messages.regenerated', ['model' => __($this->langFile . '.plural')]),
                ]);
            } else {
                if ($this->repository->find($selection)->save()) {
                    return response()->json([
                        'msgType' => 'success',
                        'msg' => __('common::messages.regenerated', ['model' => __($this->langFile . '.singular')]),
                    ]);
                }
            }
        }

        return response()->json(['message' => __($this->langFile . '.file_regeneration_failed')]);
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'block_name' => removeString($request->get('block_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'filename' => removeString($request->get('filename'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'block_name' => 'required|string|max:50|unique:cms_block,block_name',
            'filename' => 'required|regex:' . PREG_BLOCK_FILE_NAME . '|string|max:50|unique:cms_block,filename',
            'file_contents' => 'nullable|string|max:65535',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation') + __($this->langFile . '.validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['block_name'] = $rules['block_name'] . ',' . $id . ',block_id';
            $rules['filename'] = $rules['filename'] . ',' . $id . ',block_id';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
