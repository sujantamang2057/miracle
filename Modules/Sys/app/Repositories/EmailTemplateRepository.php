<?php

namespace Modules\Sys\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sys\app\Models\EmailTemplate;

class EmailTemplateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'mail_code',
        'subject',
        'variables',
        'contents',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmailTemplate::class;
    }
}
