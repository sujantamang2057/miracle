<?php

namespace Modules\Sys\app\Models;

use Modules\Common\app\Models\Common;

class MailerSetting extends Common
{
    public $table = 'sys_mailer_setting';

    public $primaryKey = 'setting_id';

    public $fillable = [
        'ssl_verify',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'smtp_host' => 'string',
        'smtp_username' => 'string',
    ];
}
