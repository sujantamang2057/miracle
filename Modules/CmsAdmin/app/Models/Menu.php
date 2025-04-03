<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Menu extends Common
{
    use SoftDeletes;

    public $table = 'cms_menu';

    public $primaryKey = 'menu_id';

    public $fillable = [
        'parent_id',
        'title',
        'slug',
        'url',
        'url_type',
        'url_target',
        'css_class',
        'tooltip',
        'show_order',
        'active',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'url' => 'string',
        'css_class' => 'string',
        'tooltip' => 'string',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public static function getParentLists($excludeID = null, $allowEmptySelect = false)
    {
        $returnArray = self::orderBy('menu_id')
            ->whereNull('parent_id')
            ->where('active', '=', '1')
            // ->where('reserved', '=', '1')
            ->where('menu_id', '<>', $excludeID)
            ->get()
            ->pluck('title', 'menu_id')
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
            $model->generateSlug($model->title, $model->slug);
        });

    }
}
