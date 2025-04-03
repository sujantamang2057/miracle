<?php

namespace Modules\Sys\app\Models;

use Modules\Common\app\Models\Common;

class SiteSetting extends Common
{
    public $table = 'sys_site_setting';

    public $primaryKey = 'setting_id';

    public $fillable = [
        'site_name',
        'site_logo',
        'meta_key',
        'meta_description',
        'seo_robots',
        'admin_email',
        'cc_admin_email',
        'cc_contact_email',
        'company_address',
        'company_tel',
        'company_tel1',
        'company_email',
        'company_website',
        'google_map',
        'google_analytics',
        'remarks',
        'updated_by',
    ];

    protected $casts = [
        'site_name' => 'string',
        'site_logo' => 'string',
        'meta_key' => 'string',
        'meta_description' => 'string',
        'seo_robots' => 'string',
        'admin_email' => 'string',
        'cc_admin_email' => 'string',
        'cc_contact_email' => 'string',
        'company_address' => 'string',
        'company_tel' => 'string',
        'company_tel1' => 'string',
        'company_email' => 'string',
        'company_website' => 'string',
        'google_map' => 'string',
        'google_analytics' => 'string',
        'remarks' => 'string',
    ];

    // get the data from site setting
    public static function __getSiteSettings()
    {
        $query = self::first();

        return $query;
    }
}
