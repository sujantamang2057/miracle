<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\ContactDataTable;
use Modules\CmsAdmin\app\Models\Contact;
use Modules\CmsAdmin\app\Repositories\ContactRepository;
use Modules\Common\app\Components\Helpers\MailHelper;
use Modules\Common\app\Http\Controllers\BackendController;

class ContactController extends BackendController
{
    /** @var ContactRepository */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->moduleName = 'cmsadmin.contacts';

        // Middleware mapping
        $middlewareMap = [
            buildPermissionMiddleware($this->moduleName, ['index', 'show', 'loadResendMail', 'resendMail', 'exportPdf']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['loadResendMail', 'resendMail']) => ['loadResendMail', 'resendMail'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['exportPdf']) => ['exportPdf'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $contactRepo;
        $this->langFile = 'cmsadmin::models/contacts';
        // define route for redirect
        $this->listRoute = route('cmsadmin.contacts.index');

    }

    /**
     * Display a listing of the Contact.
     */
    public function index(ContactDataTable $contactDataTable)
    {
        return $contactDataTable->render('cmsadmin::contacts.index');
    }

    /**
     * Display the specified Contact.
     */
    public function show($id)
    {
        if (!$contact = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::contacts.show')->with('contact', $contact);
    }

    // export pdf
    public function exportPdf($id)
    {
        if (!$contact = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $pdf = PDF::loadView('cmsadmin::contacts.pdf', compact('contact'));

        return $pdf->download('contact-' . $contact->contact_id . '.pdf');
    }

    public function loadResendMail(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $data = null;
            if ($contact = $this->findModel($id)) {
                $html = view('cmsadmin::contacts.modal_detail')->with('contact', $contact)->render();
                $data = ['html' => $html];
            }

            return response()->json($data);
        }

        return redirect($this->listRoute);
    }

    public function resendMail(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            if (!$contact = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
            $mailData = $contact->toArray();
            $nameArr = !empty($contact->from_name) ? explode(' ', $contact->from_name) : [];
            $mailData['name'] = isset($nameArr[0]) ? $nameArr[0] : '';
            $mailData['surname'] = isset($nameArr[1]) ? $nameArr[1] : '';
            $unserializedExtraDetails = !empty($contact->extra_details) ? unserialize($contact->extra_details) : [];
            $mailData['code'] = isset($unserializedExtraDetails['code']) ? $unserializedExtraDetails['code'] : '';
            $mailData['phone_no'] = isset($unserializedExtraDetails['phone_no']) ? $unserializedExtraDetails['phone_no'] : '';
            $data = ['success' => false, 'message' => __('cmsadmin::models/contacts.mail_resend_failed')];
            try {
                if ($this->__sendMail($mailData)) {
                    $contact->timestamps = false;
                    $data['success'] = true;
                    $data['message'] = __('cmsadmin::models/contacts.mail_resend_success');
                    $contact->updateQuietly(['mail_sent_count' => $contact->mail_sent_count + 1, 'mail_sent_on' => NOW]);
                } else {
                    $data['success'] = false;
                    $data['message'] = __('cmsadmin::models/contacts.mail_resend_failed');

                }
            } catch (\Exception $e) {
                $data['success'] = false;
                $data['message'] = __('cmsadmin::models/contacts.mail_resend_failed');

            }

            return response()->json($data);
        }

        return redirect($this->listRoute);
    }

    private function __sendMail($mailData)
    {
        $mailSent = false;
        if (MailHelper::setMailerConfig()) {
            // user data
            $userMail = isset($mailData['from_email']) ? $mailData['from_email'] : '';
            $mailData['email'] = $userMail;
            if (empty($userMail)) {
                return false;
            }

            // admin data
            $adminMail = getSiteSettings('admin_email');
            $adminMail = !empty($adminMail) ? $adminMail : ADMIN_EMAIL;
            $contactEmails = getSiteSettings('cc_contact_email');
            $ccAdminEmails = empty($contactEmails) ? getSiteSettings('cc_contact_email') : $contactEmails;

            $mailSent = MailHelper::sendMail($adminMail, $userMail, $mailData, 'CONTACT-ADMIN');
            $mailSent = MailHelper::sendMail($userMail, $adminMail, $mailData, 'CONTACT-USER', '', '', $ccAdminEmails);
        }

        return $mailSent;
    }
}
