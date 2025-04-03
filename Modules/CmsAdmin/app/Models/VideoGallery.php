<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class VideoGallery extends Common
{
    use SoftDeletes;

    public $table = 'cms_video_gallery';

    public $primaryKey = 'video_id';

    public $fillable = [
        'album_id',
        'caption',
        'video_url',
        'details',
        'feature_image',
        'published_date',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'caption' => 'string',
        'video_url' => 'string',
        'details' => 'string',
        'feature_image' => 'string',
    ];

    public function album(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VideoAlbum::class, 'album_id');
    }

    public function scopeActiveNow($query)
    {
        return $query->whereDate('published_date', '<=', TODAY);
    }
}
