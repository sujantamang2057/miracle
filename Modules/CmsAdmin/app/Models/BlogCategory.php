<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class BlogCategory extends Common
{
    use SoftDeletes;

    public $table = 'cms_blog_category';

    public $primaryKey = 'cat_id';

    public $fillable = [
        'cat_title',
        'cat_slug',
        'cat_image',
        'remarks',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'cat_title' => 'string',
        'cat_slug' => 'string',
        'cat_image' => 'string',
        'remarks' => 'string',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'cat_id');
    }

    public function activeBlogs($limit = null): HasMany
    {
        return $this->hasMany(Blog::class, 'cat_id')->published()->activeNow()->orderBy('show_order', 'desc')->take($limit);
    }

    public function getCategory()
    {
        return Blog::withTrashed()->where('cat_id', $this->cat_id)->get();
    }

    public static function getBlogCatList($categoryId = null, $allowEmptySelect = false)
    {
        $query = self::where('publish', '=', '1')
            ->orderBy('cat_title');

        if ($categoryId) {
            $query->withTrashed()->orWhere(function ($query) use ($categoryId) {
                $query->where('cat_id', $categoryId);
            });
        }

        $returnArray = $query->get()
            ->pluck('cat_title', 'cat_id')
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
            $model->generateSlug($model->cat_title, $model->cat_slug, 'cat_slug');
        });
    }
}
