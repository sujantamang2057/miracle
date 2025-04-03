<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Faq extends Common
{
    use SoftDeletes;

    public $table = 'cms_faq';

    public $primaryKey = 'faq_id';

    public $fillable = [
        'faq_cat_id',
        'question',
        'answer',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
    ];

    public function faqCat(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_cat_id')->withTrashed();
    }
}
