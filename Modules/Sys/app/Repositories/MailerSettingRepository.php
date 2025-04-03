<?php

namespace Modules\Sys\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sys\app\Models\MailerSetting;

class MailerSettingRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ssl_verify',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'created_by',
        'updated_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MailerSetting::class;
    }
}
