<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Common\app\Components\RegenerateImageUtility;

class ImageRegeneratorController extends ToolsController
{
    const DETAIL_NODULES = [
        'BLOG_DETAIL' => 'BLOG',
        'PAGE_DETAIL' => 'PAGE',
        'POST_DETAIL' => 'POST',
        'NEWS_DETAIL' => 'NEWS',
    ];

    public $moduleName;

    public function __construct()
    {

        $this->moduleName = 'tools.imageRegenerator';
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
        $modules = ['' => __('common::crud.text.select_any')] + ARRAY_MODULES;

        return view('tools::image_regenerator.index')
            ->with('modules', $modules);
    }

    public function getImageNames(Request $request)
    {
        if ($request->ajax()) {
            $module = $request->module_name;
            $data = ['html' => ''];
            $dataList = $this->__getImageNames($module);

            foreach ($dataList as $key => $value) {
                $data['html'] .= '<option value=' . $key . '>' . $value . '</option>';
            }

            return response()->json($data);
        } else {
            return redirect(route('tools.imageRegenerator'));
        }
    }

    public function regenerate(Request $request)
    {
        if ($request->ajax()) {
            $moduleName = $request->module_name;
            $imageName = $request->image_name;
            $offset = $request->offset ?: 0;
            $limit = $request->limit ?: IMAGE_REGENERATOR_DATA_FETCH_LIMIT;
            $this->__validate($request);

            $data = ['msg' => __('tools::image_regenerators.messages.regenerate_error')];

            $models = ARRAY_MODELS;
            $modelName = isset($models[$moduleName]) ? $models[$moduleName] : null;
            $id = null;
            $modelInstance = new $modelName;
            $pkName = $modelInstance->primaryKey;

            $modules = ARRAY_IMAGE_NAMES;
            $fieldImage = isset($modules[$imageName]) ? $modules[$imageName]['field_name'] : null;
            // fix module name
            $constModuleName = $trimmedModuleName = ($moduleName[strlen($moduleName) - 1] === 'S') ? substr($moduleName, 0, -1) : $moduleName;
            if (array_key_exists($trimmedModuleName, self::DETAIL_NODULES)) {
                $constModuleName = self::DETAIL_NODULES[$trimmedModuleName] ?: $constModuleName;
            }

            // get constants values here
            $imageDir = constant($constModuleName . '_FILE_PATH');
            $imageSizes = constant($constModuleName . '_FILE_DIMENSIONS');

            // fill up array
            $imageConfigArray = [
                'imageDir' => $imageDir,
                'imageSizes' => $imageSizes,
                'imagesKeyword' => $imageName,
            ];

            $return = RegenerateImageUtility::regenerateImages($pkName, $fieldImage, $modelName, $id, $imageConfigArray, $offset, $limit, $trimmedModuleName);

            $totalCondition = [];
            if ($modelInstance->hasAttribute('publish')) {
                $totalCondition = ['publish' => 1];
            }
            $totalCount = $modelName::where($totalCondition)->select($pkName)->get()->count();
            $data = '';
            if ($return) {
                $data = [];
                $msg = __('tools::image_regenerators.messages.regenerate_success');
                $data['result'] = json_encode($return);
                $data['msg'] = $msg;
                $data['dataFetched'] = count($return);
                $data['totalCount'] = $totalCount;
                $data['identity'] = $trimmedModuleName . '#' . $imageName;

                $data['html'] = "<div class='moduleTitle'>" . $trimmedModuleName . "</div><ul class='data'>";
                foreach ($return as $key => $value) {
                    if (!empty($value)) {
                        $data['html'] .= '<li>' . $value . '</li>';
                    }
                }
                $data['html'] .= '</ul>';
            }

            return response()->json($data);
        } else {
            return redirect(route('tools.imageRegenerator'));
        }
    }

    private function __getImageNames($module = null, $extraOption = true)
    {
        if (empty($module)) {
            return ['' => __('common::crud.text.select_any')];
        }

        $modules = ARRAY_IMAGE_NAMES;

        $matchText = '/^' . $module . '/';
        $listData = [];
        foreach ($modules as $key => $value) {
            if (preg_match($matchText, $key)) {
                $listData[$key] = $value['label'];
            }
        }
        asort($listData);
        if ($extraOption === true) {
            $listData = ['' => __('common::crud.text.select_any')] + $listData;
        }

        return $listData;
    }

    private function __validate($request)
    {

        $attributes = __('tools::image_regenerators.fields');
        $rules = [
            'module_name' => 'required',
            'image_name' => 'required',
            'limit' => 'required',
        ];

        $this->validate($request, $rules, [], $attributes);
    }
}
