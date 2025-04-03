<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Http\Request;

class TrashDataPurgerController extends ToolsController
{
    public function __construct()
    {
        $this->moduleName = 'tools.trashDataPurger';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['index']) => ['index'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        $models = PURGE_MODELS_ARR;
        $months = PURGE_MONTHS_ARR;

        return view('tools::trash_data_purger.index')
            ->with('models', $models)
            ->with('months', $months);
    }

    public function getTrashedData(Request $request)
    {
        $model = $request->input('model');
        $duration = $request->input('duration');
        $modelClass = $this->__getModelData($model, 'namespace');
        $modelName = $this->__getModelData($model, 'name');
        $titleField = $this->__getModelData($model, 'title');
        $prunableData = [];

        if (empty($modelName)) {
            $modelName = $model;
        }
        if (!empty($modelClass)) {
            // Retrieve soft-deleted records that are eligible for purging
            $purgableData = $modelClass::onlyTrashed()
                ->purgable($duration)
                ->get();
        }

        return view('tools::trash_data_purger.purgable_datatable', compact('purgableData', 'model', 'modelName', 'titleField'));
    }

    public function purgeTrashedData(Request $request)
    {
        $purgableIds = $request->input('purgable_ids');
        $model = $request->input('model');
        $modelClass = $this->__getModelData($model, 'namespace');
        $msg = __('tools::trash_data_purgers.messages.error');
        $type = 'error';

        if (!empty($modelClass) && !empty($purgableIds)) {
            // Retrieve the model class and purge the specified records
            $modelClass::whereIn((new $modelClass)->getKeyName(), $purgableIds)->forceDelete();
            $msg = __('tools::trash_data_purgers.messages.success');
            $type = 'success';
        }

        return response()->json([
            'message' => $msg,
            'type' => $type,
        ]);
    }

    private function __getModelData($model, $key)
    {
        if (empty($model) || empty($key)) {
            return '';
        }
        $purgeDataArr = PURGE_MODELS_DATA_ARR;

        return isset($purgeDataArr[$model]) && isset($purgeDataArr[$model][$key]) ? $purgeDataArr[$model][$key] : '';
    }
}
