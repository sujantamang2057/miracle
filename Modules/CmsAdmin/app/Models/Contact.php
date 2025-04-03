<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Contact extends Common
{
    use SoftDeletes;

    public $table = 'cms_contact';

    public $primaryKey = 'contact_id';

    public $fillable = [
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

    protected $casts = [
        'from_name' => 'string',
        'from_email' => 'string',
        'to_name' => 'string',
        'to_email' => 'string',
        'subject' => 'string',
        'message_body' => 'string',
        'extra_details' => 'string',
        'mail_sent_on' => 'datetime',
    ];

    public static array $rules = [
        'from_name' => 'required|string|max:255',
        'from_email' => 'required|string|email:rfc,dns|max:100',
        'to_name' => 'required|string|max:255',
        'to_email' => 'required|string|email:rfc,dns|max:100',
        'subject' => 'required|string|max:255',
        'message_body' => 'required|string|max:65535',
        'extra_details' => 'nullable|string|max:65535',
        'has_attachment' => 'required',
        'mail_sent_count' => 'nullable',
        'mail_sent_on' => 'nullable',
    ];
}
