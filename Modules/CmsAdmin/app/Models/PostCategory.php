<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class PostCategory extends Common
{
    use SoftDeletes;

    public $table = 'cms_post_category';

    public $primaryKey = 'category_id';

    public $fillable = [
        'category_name',
        'slug',
        'parent_category_id',
        'category_image',
        'remarks',
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

    public static array $rules = [
        'category_name' => 'required|string|max:191|unique:cms_post_category,category_name',
        'slug' => 'nullable|string|max:191|unique:cms_post_category,slug',
        'parent_category_id' => 'nullable|exists:cms_post_category,category_id',
        'category_image' => 'nullable|string|max:100',
        'remarks' => 'nullable|string|max:255',
        'publish' => 'required|integer|min:1|max:2',
        'reserved' => 'required|integer|min:1|max:2',
    ];

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function getCategory()
    {
        return Post::withTrashed()->where('category_id', $this->category_id)->get();
    }

    public function activePosts($limit = null): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'category_id')->published()->activeNow()->orderBy('show_order', 'desc')->take($limit);
    }

    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_category_id');
    }

    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_category_id');
    }

    /**
     * Get all the rows as an array (ready for parent dropdowns list  )
     *
     * @return array
     */
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

    /**
     * Get all data as an array (ready for dropdowns)
     *
     * @return array
     */
    public static function getPostCategoryLists($categoryId = null, $allowEmptySelect = false)
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
