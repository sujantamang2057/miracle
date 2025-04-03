<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Resource extends Common
{
    use SoftDeletes;

    public $table = 'cms_resource';

    public $primaryKey = 'resource_id';

    public $fillable = [
        'file_name',
        'display_name',
        'file_size',
        'file_type',
        'download_count',
        'view_count',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'file_name' => 'string',
        'display_name' => 'string',
        'file_size' => 'string',
        'file_type' => 'string',
    ];
}
