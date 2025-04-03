<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Gallery extends Common
{
    use SoftDeletes;

    public $table = 'cms_gallery';

    public $primaryKey = 'image_id';

    public $fillable = [
        'caption',
        'image_name',
        'show_order',
        'publish',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'caption' => 'string',
        'image_name' => 'string',
    ];

    public static array $rules = [
        'caption' => 'required|string|max:255',
        'image_name' => 'required|string|max:100',
        'publish' => 'required|integer|min:1|max:2',
    ];

    public function album(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
