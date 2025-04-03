<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Blog extends Common
{
    use SoftDeletes;

    public $table = 'cms_blog';

    public $primaryKey = 'blog_id';

    public $fillable = [
        'cat_id',
        'title',
        'slug',
        'thumb_image',
        'image',
        'detail',
        'video_url',
        'display_date',
        'publish_from',
        'publish_to',
        'remarks',
        'show_order',
        'publish',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'publish_to' => 'datetime:Y-m-d H:i',
    ];

    public function cat(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'cat_id')->withTrashed();
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogDetail::class, 'blog_id');
    }

    public function getCategory()
    {
        return BlogDetail::withTrashed()->where('blog_id', $this->blog_id)->get();
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->title, $model->slug);
        });
    }

    public function scopeActiveNow($query)
    {
        return $query->whereDate('display_date', '<=', TODAY)
            ->where(function ($query) {
                // Handle the case for publish_from
                $query->whereNull('publish_from')
                    ->orWhere('publish_from', '<=', NOW);
            })
            ->where(function ($query) {
                // Handle the case for publish_to
                $query->whereNull('publish_to')
                    ->orWhere('publish_to', '>=', NOW);
            });
    }
}
