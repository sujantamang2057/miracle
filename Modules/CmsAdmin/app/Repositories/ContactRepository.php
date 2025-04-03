<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Contact;

class ContactRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'from_name',
        'from_email',
        'to_name',
        'to_email',
        'subject',
        'message_body',
        'extra_details',
        'has_attachment',
        'mail_sent_count',
        'mail_sent_on',
        'deleted_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Contact::class;
    }
}
