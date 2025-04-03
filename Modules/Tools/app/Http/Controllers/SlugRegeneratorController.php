<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Http\Request;

class SlugRegeneratorController extends ToolsController
{
    public function __construct()
    {

        $this->moduleName = 'tools.slugRegenerator';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'regenerate']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['regenerate']) => ['regenerate'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        return view('tools::slug_regenerator.index')->with('modules', ['' => __('common::crud.text.select_any')] + SLUG_MODULES);
    }

    public function regenerate(Request $request)
    {
        if ($request->ajax()) {
            $moduleName = $request->module_name;
            $offset = $request->offset ?: 0;
            $limit = $request->limit ?: SLUG_REGENERATOR_DATA_FETCH_LIMIT;

            $state = 0;
            $errorParam = 1;
            $data = ['msg' => __('tools::slug_regenerators.messages.regenerate_error')];
            if (empty($moduleName)) {
                return response()->json($data);
            }
            $models = SLUG_MODELS;
            $modelName = isset($models[$moduleName]) ? $models[$moduleName] : null;
            $modules = SLUG_ATTR_NAMES;
            $slugField = isset($modules[$moduleName]['slugField']) ? $modules[$moduleName]['slugField'] : null;
            $titleField = isset($modules[$moduleName]['titleField']) ? $modules[$moduleName]['titleField'] : null;
            $this->__validate($request);

            if (empty($slugField) || empty($titleField)) {
                return response()->json($data);
            }

            $totalCount = $modelName::select($slugField)->get()->count();
            $fetchRecords = $modelName::offset($offset)->limit($limit)->get();
            if (!empty($fetchRecords)) {
                $data['totalCount'] = $totalCount;
                $data['msg'] = __('tools::slug_regenerators.messages.regenerate_success');
                $data['slugRegenerated'] = $fetchRecords->count();
                $data['html'] = "<div class='moduleTitle'>" . $moduleName . "</div><ul class='data'>";
                foreach ($fetchRecords as $key => $record) {
                    $pkName = $record->primaryKey;
                    $record->$slugField = '';
                    $record->save();
                    $primaryKey = $record->$pkName;
                    $data['html'] .= '<li>' . __('tools::slug_regenerators.messages.slug_regenerated', ['id' => $primaryKey, 'title' => $record->$titleField]) . '</li>';
                }
                $data['html'] .= '</ul>';
            } else {
                $data['totalCount'] = $totalCount;
                $data['slugRegenerated'] = 0;
                $data['msg'] = __('tools::slug_regenerators.messages.regenerate_success');
            }

            return response()->json($data);
        }

        return redirect(route('tools.slugRegenerator'));
    }

    private function __validate($request)
    {
        $attributes = __('tools::slug_regenerators.fields');
        $rules = [
            'module_name' => 'required',
            'limit' => 'required',
        ];

        $this->validate($request, $rules, [], $attributes);
    }
}
