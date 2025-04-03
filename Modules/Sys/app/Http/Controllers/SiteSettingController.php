<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Common\app\Rules\EmailArray;
use Modules\Sys\app\Models\SiteSetting;
use Modules\Sys\app\Repositories\SiteSettingRepository;

class SiteSettingController extends BackendController
{
    public function __construct(SiteSettingRepository $siteSettingRepo)
    {
        $this->moduleName = 'sys.siteSettings';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $siteSettingRepo;
        $this->langFile = 'sys::models/site_settings';
        // define route for redirect
        $this->listRoute = route('sys.siteSettings.index');
        // image path
        $this->imageFilePath = storage_path(SITE_SETTING_FILE_PATH);
        // image dimension
        $this->imageDimensions = json_decode(SITE_SETTING_FILE_DIMENSIONS, true);

    }

    /**
     * Display a listing of the SiteSetting.
     */
    public function index()
    {
        $siteSetting = SiteSetting::first();

        if (!empty($siteSetting)) {
            return view('sys::site_settings.show')->with('siteSetting', $siteSetting);
        } else {
            return redirect(route('sys.siteSettings.create'));
        }
    }

    /**
     * Show the form for creating a new SiteSetting.
     */
    public function create()
    {
        $siteSetting = SiteSetting::first();

        if (!empty($siteSetting)) {
            return redirect(route('sys.siteSettings.index'));
        }
        $ccAdminMails = old('cc_admin_email');
        $ccContactMails = old('cc_contact_email');

        $ccAdminMails = convertCsvToIdTextArray($ccAdminMails, $ccAdminMails);
        $ccContactMails = convertCsvToIdTextArray($ccContactMails, $ccContactMails);

        return view('sys::site_settings.create')
            ->with('ccAdminMails', $ccAdminMails)
            ->with('ccContactMails', $ccContactMails)
            ->with('id', null);
    }

    /**
     * Store a newly created SiteSetting in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $request = $this->__prepareSaveData($request);

        $siteSetting = $this->repository->create($request->all());

        // manage images
        if ($request->has('site_logo')) {
            $file = $request->site_logo;
            if (!empty($file)) {
                $this->__manageImageFile($file, $siteSetting, 'site_logo');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.siteSettings.index'));
    }

    /**
     * Show the specified SiteSetting.
     */
    public function show($id)
    {
        if (!$siteSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('sys::site_settings.show')->with('siteSetting', $siteSetting);
    }

    /**
     * Show the form for editing the specified SiteSetting.
     */
    public function edit($id)
    {
        if (!$siteSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $oldCCAdminMails = old('cc_admin_email');
        $oldCCContactMails = old('cc_contact_email');

        $ccAdminMails = $oldCCAdminMails ? $oldCCAdminMails : $siteSetting->cc_admin_email;
        $ccContactMails = $oldCCContactMails ? $oldCCContactMails : $siteSetting->cc_contact_email;

        $ccAdminMails = convertCsvToIdTextArray($ccAdminMails, $ccAdminMails);
        $ccContactMails = convertCsvToIdTextArray($ccContactMails, $ccContactMails);
        $siteSetting->site_logo_pre = $siteSetting->site_logo;

        return view('sys::site_settings.edit')
            ->with('siteSetting', $siteSetting)
            ->with('ccAdminMails', $ccAdminMails)
            ->with('ccContactMails', $ccContactMails)
            ->with('id', $siteSetting->setting_id);
    }

    /**
     * Update the specified SiteSetting in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (!$siteSetting = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $siteSetting->setting_id);
        $request = $this->__prepareSaveData($request);

        $siteSetting = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has('site_logo')) {
            $file = $request->site_logo;
            $siteSettingPre = $request->site_logo_pre;
            if (!empty($file) && $siteSettingPre != $file) {
                $this->__manageImageFile($file, $siteSetting, 'site_logo');
                // delete old image
                $this->__deleteImageFile($siteSettingPre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.siteSettings.index'));
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $request->merge([
            'site_name' => removeString($request->get('site_name'), json_decode(REPLACE_KEYWORDS_TITLE)),

        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $attributes = __($this->langFile . '.fields');
        $messages = __('common::validation') + __($this->langFile . '.validation');
        $rules = [
            'site_name' => 'required|string|max:50|unique:sys_site_setting,site_name',
            'site_logo' => 'required|string|max:255',
            'meta_key' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
            'seo_robots' => 'nullable|string|max:65535',
            'admin_email' => 'required|email:rfc,dns|max:100',
            'cc_admin_email' => ['nullable'],
            'cc_contact_email' => ['nullable'],
            'company_address' => 'nullable|string|max:255',
            'company_tel' => 'nullable|string|max:12|regex:' . PREG_SITE_SETTING_TEL,
            'company_tel1' => 'nullable|string|max:12|regex:' . PREG_SITE_SETTING_TEL,
            'company_email' => 'nullable|email:rfc,dns|max:100',
            'company_website' => 'nullable|url|max:100',
            'google_map' => 'nullable|string|max:65535',
            'google_analytics' => 'nullable|string|max:65535',
            'remarks' => 'nullable|string|max:65535',
        ];
        $emailValidation = new EmailArray;
        $rules['cc_admin_email'][] = $emailValidation;
        $rules['cc_contact_email'][] = $emailValidation;
        if (!empty($id)) {
            $rules['site_name'] = $rules['site_name'] . ',' . $id . ',setting_id';
        }
        $this->validate($request, $rules, $messages, $attributes);
    }

    public function __prepareSaveData($request)
    {
        $ccAdminEmails = $request->get('cc_admin_email');
        $ccContactEmails = $request->get('cc_contact_email');
        $request->merge([
            'cc_admin_email' => !empty($ccAdminEmails) ? implode(',', $ccAdminEmails) : $ccAdminEmails,
            'cc_contact_email' => !empty($ccContactEmails) ? implode(',', $ccContactEmails) : $ccContactEmails,
        ]);

        return $request;
    }
}
