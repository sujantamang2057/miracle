<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class PostDetail extends Common
{
    use SoftDeletes;

    public $table = 'cms_post_detail';

    public $primaryKey = 'detail_id';

    public $fillable = [
        'post_id',
        'title',
        'image',
        'detail',
        'layout',
        'show_order',
        'publish',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'title' => 'string',
        'image' => 'string',
        'detail' => 'string',
        'layout' => 'integer',
    ];

    public static array $rules = [
        'title' => 'nullable|string|max:255',
        'image' => 'nullable|string|max:255',
        'detail' => 'nullable|string|max:65535',
        'layout' => 'nullable|integer|min:1|max:4',
        'publish' => 'required|integer|min:1|max:2',
    ];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
