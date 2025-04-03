<?php

namespace Modules\Common\app\Components\Helpers;

use Crypt;
use Mail;
use Modules\Common\app\Emails\CommonMail;
use Modules\Sys\app\Models\MailerSetting;

class MailHelper
{
    /**
     * sends mail
     *
     * @param  string  $to  receiver of mail
     * @param  string  $replyTo  sender of mail
     * @param  array  $mailData  data required for mail
     * @param  string  $mailCode  code of email template
     * @param  string  $subject  subject of mail
     * @param  string  $content  content of mail
     * @param  array  $cc  array of cc emails
     * @param  array  $bcc  array of bcc emails
     * @param  array  $attachedFiles  data of files to be attached   e.g: [['path' => 'filebox/test.png', 'fileName' => 'Test']]
     * @return bool returns true if mail is sent
     */
    public static function sendMail($to, $replyTo = '', $mailData = [], $mailCode = '', $subject = '', $content = '', $cc = null, $bcc = null, $attachedFiles = [])
    {
        if (empty($to)) {
            return false;
        }
        $attachedFiles = is_array($attachedFiles) ? $attachedFiles : [];
        $commonMail = new CommonMail($mailCode, $replyTo, $mailData, $subject, $content, $attachedFiles);

        $mailObj = Mail::to($to);

        // remove duplicate mails
        $cc = self::__removeDuplicateMails($to, $cc);
        $checkBcc = '';
        if (!empty($cc)) {
            $checkBcc = $cc;
            $mailObj->cc($cc);
        }
        $bcc = self::__removeDuplicateMails($checkBcc, $bcc);
        if (!empty($bcc)) {
            $mailObj->bcc($bcc);
        }

        return $mailObj->send($commonMail);
    }

    /**
     * gets unique mails from provided data
     *
     * @param  array/string $mailsCompareAgainst mail array to compare against
     * @param  array/string $mailsCompareWith	 mail array to compare from
     * @return array array of mails
     */
    private static function __removeDuplicateMails($mailsCompareAgainst, $mailsCompareWith)
    {
        if (empty($mailsCompareAgainst) || empty($mailsCompareWith)) {
            return $mailsCompareWith;
        }

        $mailsCompareAgainst = is_array($mailsCompareAgainst) ? $mailsCompareAgainst : explode(',', $mailsCompareAgainst);
        $mailsCompareWith = is_array($mailsCompareWith) ? $mailsCompareWith : explode(',', $mailsCompareWith);
        $diffArr = array_diff($mailsCompareWith, $mailsCompareAgainst);

        return array_unique($diffArr);
    }

    private static function __getMailerSettings()
    {
        return MailerSetting::orderBy('setting_id', 'desc')->first();
    }

    public static function setMailerConfig()
    {
        $mailerSetting = self::__getMailerSettings();
        $retVal = false;
        if (!empty($mailerSetting)) {
            $smtpConfig = config('mail.mailers.smtp');
            $smtpConfig['host'] = $mailerSetting->smtp_host;
            $smtpConfig['port'] = $mailerSetting->smtp_port;
            $smtpConfig['username'] = $mailerSetting->smtp_username;
            $smtpConfig['password'] = !empty($mailerSetting->smtp_password) ? Crypt::decryptString($mailerSetting->smtp_password) : '';
            $smtpConfig['encryption'] = $mailerSetting->ssl_verify == 1 ? 'ssl' : 'tls';
            if ($mailerSetting->ssl_verify == 2 || env('APP_ENV', 'local') == 'local') {
                $smtpConfig['verify_peer'] = false;
            }
            config(['mail.mailers.smtp' => $smtpConfig]);

            $retVal = true;
        }

        return $retVal;
    }
}
