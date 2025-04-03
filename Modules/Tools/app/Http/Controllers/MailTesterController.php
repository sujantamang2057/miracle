<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Common\app\Components\Helpers\MailHelper;
use Modules\Common\app\Rules\EmailArray;
use Modules\Sys\app\Models\MailerSetting;

class MailTesterController extends ToolsController
{
    public function __construct()
    {

        $this->moduleName = 'tools.mailTester';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'sendMail']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['sendMail']) => ['sendMail'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        $mailerSetting = MailerSetting::first();
        if (empty($mailerSetting)) {
            return redirect(route('sys.mailerSettings.create'));
        }

        $ccMails = old('cc_email');
        $bccMails = old('bcc_email');
        $ccMails = convertCsvToIdTextArray($ccMails);
        $bccMails = convertCsvToIdTextArray($bccMails);

        return view('tools::mail_tester.index')
            ->with('mailerSetting', $mailerSetting)
            ->with('ccMails', $ccMails)
            ->with('bccMails', $bccMails);
    }

    public function sendMail(Request $request)
    {
        if ($request->ajax()) {
            $validationErrors = $this->__validate($request);

            // check if the validation error exists
            if ($validationErrors) {
                return response()->json([
                    'errors' => $validationErrors,
                ]);
            }

            $data = [
                'message' => __('tools::models/mail_testers.mail_test_failed'),
                'success' => 0,
            ];
            if ($this->__sendMail($request->all())) {
                $data = [
                    'message' => __('tools::models/mail_testers.mail_test_success'),
                    'success' => 1,
                ];
            }

            return response()->json($data);
        }

        return redirect(route('tools.mailtester'));
    }

    private function __validate($request)
    {
        $attributes = __('tools::models/mail_testers.fields');
        $messages = __('common::validation');
        $emailValidation = new EmailArray;
        $rules = [
            'subject' => 'required',
            'email' => 'required|email',
            'cc_email' => [$emailValidation],
            'bcc_email' => [$emailValidation],
            'message' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages, $attributes);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        return null;
    }

    private function __sendMail($mailData)
    {
        $email = isset($mailData['email']) ? $mailData['email'] : null;
        $ccEmails = isset($mailData['cc_email']) ? $mailData['cc_email'] : null;
        $bccEmails = isset($mailData['bcc_email']) ? $mailData['bcc_email'] : null;
        $subject = isset($mailData['subject']) ? $mailData['subject'] : null;
        $message = isset($mailData['message']) ? $mailData['message'] : null;

        $mailSent = false;
        $mailSent = MailHelper::sendMail($email, '', $mailData, '', $subject, $message, $ccEmails, $bccEmails);

        return $mailSent;
    }
}
