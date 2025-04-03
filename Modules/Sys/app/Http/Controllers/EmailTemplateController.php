<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\DataTables\EmailTemplateDataTable;
use Modules\Sys\app\DataTables\EmailTemplateTrashDataTable;
use Modules\Sys\app\Models\EmailTemplate;
use Modules\Sys\app\Repositories\EmailTemplateRepository;

class EmailTemplateController extends BackendController
{
    public function __construct(EmailTemplateRepository $emailTemplateRepo)
    {
        $this->moduleName = 'sys.emailTemplates';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'trashList', 'regenerate']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['regenerate']) => ['regenerate'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $emailTemplateRepo;
        $this->langFile = 'sys::models/email_templates';
        $this->listRoute = route('sys.emailTemplates.index');
        $this->trashListRoute = route('sys.emailTemplates.trashList');
        $this->detailRouteName = 'sys.emailTemplates.show';
    }

    /**
     * Display a listing of the EmailTemplate.
     */
    public function index(EmailTemplateDataTable $emailTemplateDataTable)
    {
        return $emailTemplateDataTable->render('sys::email_templates.index');
    }

    /**
     * Display a listing of the Trashed EmailTemplate.
     */
    public function trashList(EmailTemplateTrashDataTable $emailTemplateTrashDataTable)
    {
        return $emailTemplateTrashDataTable->render('sys::email_templates.trash');
    }

    /**
     * Show the form for creating a new EmailTemplate.
     */
    public function create()
    {
        $variables = old('variables');
        $variables = convertCsvToIdTextArray($variables, $variables);

        return view('sys::email_templates.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'))
            ->with('variables', $variables);
    }

    /**
     * Store a newly created EmailTemplate in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $request = $this->__prepareSaveData($request);
        $this->__validateVariables($request);
        $input = $request->all();

        $emailTemplate = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.emailTemplates.show', ['email_template' => $emailTemplate->template_id]));
    }

    /**
     * Display the specified EmailTemplate.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$emailTemplate = EmailTemplate::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($emailTemplate->trashed()) {
                return view('sys::email_templates.show-trash')
                    ->with('emailTemplate', $emailTemplate)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('sys.emailTemplates.show', ['emailTemplate' => $emailTemplate->template_id]));
            }
        } else {
            if (!$emailTemplate = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('sys::email_templates.show')
            ->with('emailTemplate', $emailTemplate)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified EmailTemplate.
     */
    public function edit($id)
    {
        if (!$emailTemplate = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $oldVariables = old('variables');
        $variables = $oldVariables ? $oldVariables : $emailTemplate->variables;
        $variables = convertCsvToIdTextArray($variables, $variables);

        return view('sys::email_templates.edit')
            ->with('id', $emailTemplate->resource_id)
            ->with('publish', getOldData('publish', $emailTemplate->publish))
            ->with('reserved', getOldData('reserved', $emailTemplate->reserved))
            ->with('emailTemplate', $emailTemplate)
            ->with('variables', $variables);
    }

    /**
     * Update the specified EmailTemplate in storage.
     */
    public function update($id, Request $request)
    {
        if (!$emailTemplate = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $emailTemplate->template_id);
        $request = $this->__prepareSaveData($request);
        $this->__validateVariables($request);
        $input = $request->all();

        $emailTemplate = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.emailTemplates.show', $id));
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

        return response()->json(['message' => __($this->langFile . '.email_regeneration_failed')]);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'name' => removeString($request->get('name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:sys_email_template,name',
            'mail_code' => 'required|regex:' . PREG_EMAIL_TEMPLATE_CODE . '|string|max:15|unique:sys_email_template,mail_code',
            'subject' => 'required|string|max:150',
            'variables' => ['nullable'],
            'contents' => 'required|string',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages1 = __('common::validation') + __($this->langFile . '.validation');
        $messages2 = EmailTemplate::$messages;
        $messages = $messages1 + $messages2;
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['name'] = $rules['name'] . ',' . $id . ',template_id';
            $rules['mail_code'] = $rules['mail_code'] . ',' . $id . ',template_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    private function __validateVariables($request)
    {
        $request->validate(['variables' => 'max:255'], ['variables.max' => __($this->langFile . '.message.variables_exceeded_field_limit')]);
    }

    public function __prepareSaveData($request)
    {
        $variables = $request->get('variables');

        $request->merge([
            'variables' => !empty($variables) ? implode(',', $variables) : $variables,
        ]);

        return $request;
    }
}
