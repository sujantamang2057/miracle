<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class NewsCategory extends Common
{
    use SoftDeletes;

    public $table = 'cms_news_category';

    public $primaryKey = 'category_id';

    public $fillable = [
        'category_name',
        'slug',
        'parent_category_id',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'category_name' => 'string',
        'slug' => 'string',
    ];

    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public function getCategory()
    {
        return News::withTrashed()->where('category_id', $this->category_id)->get();
    }

    public function activeNews($limit = null): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class, 'category_id')->published()->activeNow()->orderBy('show_order', 'desc')->take($limit);
    }

    public function parent()
    {
        return $this->belongsTo(NewsCategory::class, 'parent_category_id');
    }

    public static function getParentLists($excludeID = null, $allowEmptySelect = false)
    {
        $returnArray = self::orderBy('category_id')
            ->whereNull('parent_category_id')
            ->where('publish', '=', '1')
            ->where('category_id', '<>', $excludeID)
            ->get()
            ->pluck('category_name', 'category_id')
            ->toArray();
        if ($allowEmptySelect) {
            $returnArray = getExtraOption() + $returnArray;
        }

        return $returnArray;
    }

    public static function getNewsCategoryLists($categoryId = null, $allowEmptySelect = false)
    {
        $query = self::where('publish', '=', '1')
            ->orderBy('category_name');

        if ($categoryId) {
            $query->withTrashed()->orWhere(function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        $returnArray = $query->get()
            ->pluck('category_name', 'category_id')
            ->toArray();
        if ($allowEmptySelect) {
            $returnArray = getExtraOption() + $returnArray;
        }

        return $returnArray;
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->category_name, $model->slug);
        });
    }
}
