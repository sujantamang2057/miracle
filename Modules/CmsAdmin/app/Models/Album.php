<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Album extends Common
{
    use SoftDeletes;

    public $table = 'cms_album';

    public $primaryKey = 'album_id';

    public $fillable = [
        'date',
        'title',
        'slug',
        'detail',
        'cover_image_id',
        'image_count',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'detail' => 'string',
    ];

    public function galleries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Gallery::class, 'album_id');
    }

    public function getCategory()
    {
        return Gallery::withTrashed()->where('album_id', $this->album_id)->get();
    }

    public function coverImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Gallery::class, 'image_id', 'cover_image_id');
    }

    public function scopeActiveNow($query)
    {
        return $query->whereDate('date', '<=', TODAY);
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->title, $model->slug);
        });

    }
}
