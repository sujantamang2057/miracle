<?php

namespace Modules\CmsAdmin\app\Models;

use Modules\Common\app\Models\Common;

class CspHeader extends Common
{
    public $table = 'csp_header';

    public $primaryKey = 'csp_id';

    public $fillable = [
        'directive',
        'keyword',
        'value',
        'schema',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'directive' => 'string',
        'keyword' => 'string',
        'value' => 'string',
        'schema' => 'string',

    ];
}
