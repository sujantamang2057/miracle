<?php

namespace App\Providers;

use Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Sys\app\Models\MailerSetting;

class MailerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure that the database connection is established
        try {
            // Check if 'sys_mailer_setting' table exists
            if (Schema::hasTable('sys_mailer_setting')) {
                $mailerSetting = MailerSetting::first();
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
                    // Update the mail config dynamically
                    config(['mail.mailers.smtp' => $smtpConfig]);
                }
            }
        } catch (\Exception $e) {
            // Handle any errors
            \Log::error('MailerServiceProvider: Error fetching mail settings: ' . $e->getMessage());
        }
    }
}
