<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class VideoAlbum extends Common
{
    use SoftDeletes;

    public $table = 'cms_video_album';

    public $primaryKey = 'album_id';

    public $fillable = [
        'album_name',
        'slug',
        'album_date',
        'description',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'album_name' => 'string',
        'slug' => 'string',
        'detail' => 'string',
    ];

    public function videoGalleries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VideoGallery::class, 'album_id', 'album_id');
    }

    public function getCategory()
    {
        return VideoGallery::withTrashed()->where('album_id', $this->album_id)->get();
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->album_name, $model->slug);
        });

    }

    public function scopeActiveNow($query)
    {
        return $query->whereDate('album_date', '<=', TODAY);
    }
}
