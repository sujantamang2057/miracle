<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class News extends Common
{
    use SoftDeletes;

    public $table = 'cms_news';

    public $primaryKey = 'news_id';

    public $fillable = [
        'news_title',
        'slug',
        'category_id',
        'description',
        'banner_image',
        'feature_image',
        'published_date',
        'publish_date_from',
        'publish_date_to',
        'show_order',
        'publish',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'news_title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'banner_image' => 'string',
        'feature_image' => 'string',
        'publish_date_from' => 'datetime:Y-m-d H:i',
        'publish_date_to' => 'datetime:Y-m-d H:i',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(NewsCategory::class, 'category_id')->withTrashed();
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NewsDetail::class, 'news_id');
    }

    public function getCategory()
    {
        return NewsDetail::withTrashed()->where('news_id', $this->news_id)->get();
    }

    /**
     * Scope a query to only include news of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveNow($query)
    {
        return $query->whereDate('published_date', '<=', TODAY)
            ->where(function ($query) {
                // Handle the case for publish_date_from
                $query->whereNull('publish_date_from')
                    ->orWhere('publish_date_from', '<=', NOW);
            })
            ->where(function ($query) {
                // Handle the case for publish_date_to
                $query->whereNull('publish_date_to')
                    ->orWhere('publish_date_to', '>=', NOW);
            });
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->news_title, $model->slug);
        });
    }
}
