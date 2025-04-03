<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\Models\MailerSetting;
use Modules\Sys\app\Repositories\MailerSettingRepository;

class MailerSettingController extends BackendController
{
    public function __construct(MailerSettingRepository $mailerSettingRepo)
    {
        $this->moduleName = 'sys.mailerSettings';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $mailerSettingRepo;
        $this->langFile = 'sys::models/mailer_settings';
        // define route for redirect
        $this->listRoute = route('sys.mailerSettings.index');
    }

    public function index()
    {
        $mailerSetting = MailerSetting::first();

        if (!empty($mailerSetting)) {
            return view('sys::mailer_settings.show')->with('mailerSetting', $mailerSetting);
        } else {
            return redirect(route('sys.mailerSettings.create'));
        }
    }

    /**
     * Show the form for creating a new MailerSetting.
     */
    public function create()
    {
        $mailerSetting = MailerSetting::first();

        if (!empty($mailerSetting)) {
            return redirect(route('sys.mailerSettings.index'));
        }

        return view('sys::mailer_settings.create')
            ->with('id', null)
            ->with('smtp_password', null)
            ->with('ssl_verify', getOldData('ssl_verify'));
    }

    /**
     * Store a newly created MailerSetting in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $mailerSetting = $this->repository->create($request->all());

        // encrypt the password before saving
        $mailerSetting->smtp_password = Crypt::encryptString($request->smtp_password);
        $mailerSetting->save();

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.mailerSettings.index'));
    }

    /**
     * Display the specified MailerSetting.
     */
    public function show($id)
    {
        if (!$mailerSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('sys::mailer_settings.show')->with('mailerSetting', $mailerSetting);
    }

    /**
     * Show the form for editing the specified MailerSetting.
     */
    public function edit($id)
    {
        if (!$mailerSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $mailerSetting->smtp_password_pre = $mailerSetting->smtp_password;

        if (!empty($mailerSetting->smtp_password_pre)) {
            $mailerSetting->smtp_password = Crypt::decryptString($mailerSetting->smtp_password_pre);
        }

        return view('sys::mailer_settings.edit')
            ->with('id', $mailerSetting->setting_id)
            ->with('ssl_verify', getOldData('ssl_verify', $mailerSetting->ssl_verify))
            ->with('smtp_password', $mailerSetting->smtp_password)
            ->with('mailerSetting', $mailerSetting);
    }

    /**
     * Update the specified MailerSetting in storage.
     */
    public function update($id, Request $request)
    {
        if (!$mailerSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $mailerSetting->setting_id);
        $mailerSetting = $this->repository->update($request->all(), $id);

        // Update the SMTP password
        if ($request->has('smtp_password')) {
            $password = $request->smtp_password;
            if (!empty($password)) {
                $mailerSetting->smtp_password = Crypt::encryptString($request->smtp_password);
            } else {
                $mailerSetting->smtp_password = $request->smtp_password_pre;
            }
            $mailerSetting->save();
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.mailerSettings.index'));
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $ssl_verify = ($request->get('ssl_verify') == 'on') ? 1 : 2;
        $request->merge([
            'ssl_verify' => $ssl_verify,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'ssl_verify' => 'required',
            'smtp_host' => 'required|string|max:50',
            'smtp_port' => 'required|int||max:999',
            'smtp_username' => 'required|string|max:50|unique:sys_mailer_setting,smtp_username',
            'smtp_password' => 'required|max:200',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['smtp_username'] = $rules['smtp_username'] . ',' . $id . ',setting_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }
}
