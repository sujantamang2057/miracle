<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class FaqCategory extends Common
{
    use SoftDeletes;

    public $table = 'cms_faq_category';

    public $primaryKey = 'faq_cat_id';

    public $fillable = [
        'faq_cat_name',
        'slug',
        'faq_cat_image',
        'remarks',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'faq_cat_name' => 'string',
        'slug' => 'string',
        'faq_cat_image' => 'string',
        'remarks' => 'string',
    ];

    public function faq(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Faq::class, 'faq_cat_id');
    }

    public function getCategory()
    {
        return Faq::withTrashed()->where('faq_cat_id', $this->faq_cat_id)->get();
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->faq_cat_name, $model->slug);
        });
    }

    public static function getFaqCategoryLists($categoryId = null, $allowEmptySelect = false)
    {
        $query = self::where('publish', '=', '1')
            ->orderBy('faq_cat_name');

        if ($categoryId) {
            $query->withTrashed()->orWhere(function ($query) use ($categoryId) {
                $query->where('faq_cat_id', $categoryId);
            });
        }

        $returnArray = $query->get()
            ->pluck('faq_cat_name', 'faq_cat_id')
            ->toArray();
        if ($allowEmptySelect) {
            $returnArray = getExtraOption() + $returnArray;
        }

        return $returnArray;
    }

    public function activeFaq(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Faq::class, 'faq_cat_id')->published()->orderBy('show_order', 'desc');
    }
}
