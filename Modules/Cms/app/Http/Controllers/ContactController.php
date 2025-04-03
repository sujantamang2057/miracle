<?php

namespace Modules\Cms\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CmsAdmin\app\Models\Contact;
use Modules\Common\app\Components\Helpers\MailHelper;
use Modules\Common\app\Http\Controllers\FrontendController;
use Modules\Common\app\Rules\Recaptcha;

class ContactController extends FrontendController
{
    public function index()
    {
        $data = session('contact', []);

        return view('cms::contact.index', compact('data'));
    }

    public function confirm(Request $request)
    {
        $rules = [
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|string|between:10,100',
            'code' => 'required|regex:/^\+\d{1,3}$/',
            'phone_no' => 'required|numeric|digits_between:5,10',
            'message_body' => 'required|string|max:1000',
        ];
        if (env('ENABLE_RECAPTCHA', false)) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha];

        }

        $attribute1 = __('cms::contact.fields');
        $attribute2 = __('common::crud.fields');
        $attributes = $attribute1 + $attribute2;
        $messages = __('common::validation');

        $this->validate($request, $rules, $messages, $attributes);
        $data = $request->all();

        unset($data['_token']);
        unset($data['g-recaptcha-response']);
        session(['contact' => $data]);

        return view('cms::contact.confirm')->with($data);
    }

    public function complete(Request $request)
    {
        if ($request->confirmed) {
            $mailTemplate = getEmailTemplate('CONTACT-USER');
            $contact = new Contact;
            $contactData = session('contact');
            $name = isset($contactData['name']) ? $contactData['name'] : '';
            $name .= isset($contactData['surname']) ? ' ' . $contactData['surname'] : '';
            $subject = $mailTemplate?->subject;
            $contact->subject = empty($subject) ? __('cms::contact.subject') : $subject;
            $contact->from_name = $name;
            $contact->from_email = isset($contactData['email']) ? ' ' . $contactData['email'] : '';
            $contact->message_body = isset($contactData['message_body']) ? ' ' . $contactData['message_body'] : '';
            $contact->extra_details = serialize([
                'code' => isset($contactData['code']) ? ' ' . $contactData['code'] : '',
                'phone_no' => isset($contactData['phone_no']) ? ' ' . $contactData['phone_no'] : '',
            ]);
            $contact->to_name = config('mail.from.name');
            $contact->to_email = config('mail.from.address');
            if ($contact->save()) {
                if ($this->__sendMail($contactData)) {
                    $contact->timestamps = false;
                    $contact->updateQuietly(['mail_sent_count' => 1, 'mail_sent_on' => NOW]);
                }
                session()->forget('contact');

                return view('components.blocks.contact_complete');
            }
        }

        return redirect(route('cms.contact.index'));
    }

    private function __sendMail($mailData)
    {
        $mailSent = false;
        // user data
        $userMail = isset($mailData['email']) ? $mailData['email'] : '';
        if (empty($userMail)) {
            return false;
        }

        // admin data
        $adminMail = getSiteSettings('admin_email');
        $adminMail = !empty($adminMail) ? $adminMail : ADMIN_EMAIL;
        $ccAdminEmails = getSiteSettings('cc_admin_email');
        $contactEmails = getSiteSettings('cc_contact_email');
        $concatWith = !empty($ccAdminEmails && $contactEmails) ? ',' : '';
        $ccEmails = $ccAdminEmails . $concatWith . $contactEmails;

        $mailSent = MailHelper::sendMail($adminMail, $userMail, $mailData, 'CONTACT-ADMIN', '', '', $ccEmails);
        $mailSent = MailHelper::sendMail($userMail, $adminMail, $mailData, 'CONTACT-USER');

        return $mailSent;
    }
}
