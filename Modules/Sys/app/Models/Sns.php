<?php

namespace Modules\Sys\app\Models;

use Modules\Common\app\Models\Common;

class Sns extends Common
{
    public $table = 'sys_sns';

    public $primaryKey = 'sns_id';

    public $fillable = [
        'title',
        'icon',
        'class',
        'url',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
    ];
}
