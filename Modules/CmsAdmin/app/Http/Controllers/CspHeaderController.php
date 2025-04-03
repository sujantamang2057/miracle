<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\CmsAdmin\app\DataTables\CspHeaderDataTable;
use Modules\CmsAdmin\app\Models\CspHeader;
use Modules\CmsAdmin\app\Repositories\CspHeaderRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class CspHeaderController extends BackendController
{
    /** @var CspHeaderRepository */
    private $cspHeaderRepository;

    public function __construct(CspHeaderRepository $cspHeaderRepo)
    {
        $this->moduleName = 'cmsadmin.cspHeaders';
        $middlewareMap = [
            buildPermissionMiddleware($this->moduleName, ['index', 'destroy', 'togglePublish', 'reorder']) => ['index'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];

        applyMiddleware($this, $middlewareMap);
        $this->repository = $cspHeaderRepo;
        $this->langFile = 'cmsadmin::models/csp_headers';
        $this->listRoute = route('cmsadmin.cspHeaders.index');
    }

    /**
     * Display a listing of the CspHeader.
     */
    public function index(CspHeaderDataTable $cspHeaderDataTable)
    {
        return $cspHeaderDataTable->render('cmsadmin::csp_headers.index');
    }

    public function edit($id)
    {
        $cspHeader = $this->repository->find($id);
        if (empty($cspHeader)) {
            Flash::error(__('models/cspHeaders.singular') . ' ' . __('messages.not_found'))->important();

            return redirect(route('cmsadmin.cspHeaders.index'));
        }
        $selectDirectives = SELECT_DIRECTIVES;
        $selectKeywords = SELECT_KEYWORDS;
        $selectSchemas = SELECT_SCHEMAS;
        $Keyword = $cspHeader->keyword;
        $keywordsArray = explode(',', $Keyword);
        $alreadySelectedKeyword = array_combine($keywordsArray, $keywordsArray);
        if (!empty($cspHeader->value)) {
            $value = $cspHeader->value;
            $valuesArray = explode(',', $value);
            $alreadySelectedValue = array_combine($valuesArray, $valuesArray);

        } else {
            $alreadySelectedValue = [];
        }

        $Schema = $cspHeader->schema;
        $schemasArray = explode(',', $Schema);
        $alreadySelectedSchema = array_combine($schemasArray, $schemasArray);

        return view('cmsadmin::csp_headers.edit')
            ->with('cspHeader', $cspHeader)
            ->with('selectDirectives', $selectDirectives)
            ->with('selectKeywords', $selectKeywords)
            ->with('selectSchemas', $selectSchemas)
            ->with('alreadySelectedKeyword', $alreadySelectedKeyword)
            ->with('alreadySelectedValue', $alreadySelectedValue)
            ->with('alreadySelectedSchema', $alreadySelectedSchema)
            ->with('id', $id)
            ->with('publish', getOldData('publish', $cspHeader->publish));

    }

    /**
     * Update the specified CspHeader in storage.
     */
    public function update($id, Request $request)
    {
        if (!($cspHeader = $this->findModel($id))) {
            return redirect($this->listRoute);
        }
        if ($request->ajax()) {

            $validationErrors = $this->__validate($request, $request->csp_header);
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
        $request = $this->__sanitize($request);
        if (!isset($request->value)) {
            $request->merge([
                'value' => null,
            ]);
        }
        if (!isset($request->schema)) {
            $request->merge([
                'schema' => null,
            ]);
        }
        $input = $request->all();

        $input['directive'] = implode(',', $request->directive);
        $input['keyword'] = implode(',', $request->keyword);
        if (!empty($input['schema'])) {
            $input['schema'] = implode(',', $request->schema);
        }
        if (!empty($input['value'])) {
            $input['value'] = implode(',', $request->value);
        }
        $cspHeader = $this->repository->update($input, $id);
        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.cspHeaders.index'));
    }

    public function regenerate()
    {
        $blockPath = base_path('config');
        try {
            $policies = CspHeader::where('publish', 1)->get();
            $this->__makeConstant($policies, $blockPath);
            Flash::success(__('common::messages.regenerated', ['model' => __($this->langFile . '.singular')]))->important();

            return redirect($this->listRoute);
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }
    }

    /**
     * Remove the specified CspHeader from storage.
     *
     * @throws \Exception
     */
    private function __sanitize($request)
    {
        $publish = $request->get('publish') == 'on' ? 1 : 2;
        $request->merge([
            'publish' => $publish,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'directive' => 'required|max:30|unique:csp_header,directive,' . $id . ',csp_id',
            'keyword' => 'required|max:50',
            'value.*' => 'nullable|url|max:255',
            'schema' => 'nullable|max:25',

        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        $this->validate($request, $rules, $messages, $attributes);

    }

    private function __makeConstant($policies, $blockPath)
    {
        $content = "<?php\n/**\n * constants-policy.php\n * DO NOT Modify contents manually, will be REPLACED from backend/Database */\n\n";
        $content .= "define('POLICY_CONSTANT', [\n";

        foreach ($policies as $policy) {
            $value = str_replace(',', ' ', $policy->value);
            $schema = str_replace(',', ' ', $policy->schema);
            $keywords = array_map(function ($item) {
                return "'" . trim($item) . "'";
            }, explode(',', $policy->keyword));

            $combineAll = implode(' ', array_merge($keywords, [$value, $schema]));
            $content .= "        '" . $policy->directive . "' => \"" . $combineAll . "\",\n";

        }
        $content .= "]);\n";
        File::put($blockPath . DS . 'constants-policy.php', $content);

    }
}
