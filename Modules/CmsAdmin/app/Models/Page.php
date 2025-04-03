<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Common\app\Models\Common;
use Modules\Sys\app\Models\User;

class Page extends Common
{
    use SoftDeletes;

    public $table = 'cms_page';

    public $primaryKey = 'page_id';

    public $fillable = [
        'page_title',
        'slug',
        'page_type',
        'page_file_name',
        'meta_keyword',
        'meta_description',
        'description',
        'banner_image',
        'published_date',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PageDetail::class, 'page_id');
    }

    public function getCategory()
    {
        return PageDetail::withTrashed()->where('page_id', $this->page_id)->get();
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $model->generateSlug($model->page_title, $model->slug);

            $originalContent = $model->getOriginal('description');
            if (!empty($originalContent) && $model->description != $originalContent) {
                self::regeneratePage($model->publish, $model->page_id, $model->page_file_name, $originalContent, 'backup');
            }
        });

        self::saved(function ($model) {
            if (!empty($model->description) && $model->page_type == 2) {
                self::regeneratePage($model->publish, $model->page_id, $model->page_file_name, $model->description);
            }
        });
    }

    public static function regeneratePage($publish = 1, $id = null, $pageName = null, $content = null, $mode = '')
    {
        $pagesPath = base_path('resources/views/components/pages_dynamic/');
        try {
            // Check & CreateDir
            File::ensureDirectoryExists($pagesPath);

            if ($mode == 'backup') {
                $pagesPath .= DS . '_backup_' . date('Hi');
                // Check & CreateDir
                File::ensureDirectoryExists($pagesPath);
            }
            if (!empty($pageName)) {
                $filename = $pageName;
            } else {
                $filename = $id . '_page';
            }
            if ($publish == 2) {
                $content = '@php // Page publish is OFF, Set ON to show @endphp';
            }
            $warningContent = '@php // DO NOT Modify contents manually, will be REPLACED from backend/Database @endphp' . EOL;
            $warningContent .= '@php // File Generated at: ' . FULL_DATE . ' @endphp' . EOL . EOL;
            $saved = File::put($pagesPath . DS . $filename . '.blade.php', $warningContent . $content);
            if ($saved && !empty($id)) {
                DB::update(
                    'UPDATE cms_page set page_file_name = ? where page_id = ?',
                    [$filename, $id]
                );
            }
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(User::class, 'updated_by')->withTrashed();
    }
}
