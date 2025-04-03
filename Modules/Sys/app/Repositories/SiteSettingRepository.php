<?php

namespace Modules\Sys\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sys\app\Models\SiteSetting;

class SiteSettingRepository extends BaseRepository
{
    protected $fieldSearchable = [
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

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SiteSetting::class;
    }
}
