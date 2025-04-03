<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Common\app\Models\Common;
use Modules\Sys\app\Models\User;

class Block extends Common
{
    use SoftDeletes;

    public $table = 'cms_block';

    public $primaryKey = 'block_id';

    public $fillable = [
        'block_name',
        'filename',
        'file_contents',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'block_name' => 'string',
        'filename' => 'string',
        'file_contents' => 'string',
    ];

    public static function regenerateBlock($publish = 1, $filename = null, $content = null, $mode = '')
    {
        $blockPath = base_path('resources/views/components/blocks/');
        try {
            if (!File::exists($blockPath)) {
                File::makeDirectory($blockPath); // creates directory
            }
            if ($mode == 'backup') {
                $blockPath .= DS . '_backup_' . date('Hi');

                if (!File::exists($blockPath)) {
                    File::makeDirectory($blockPath); // creates directory
                }
            }
            if ($publish == 2) {
                $content = '@php // Block Status is OFF, Set ON to show @endphp';
            }
            $warningContent = '@php // DO NOT Modify contents manually, will be REPLACED from backend/Database @endphp' . EOL;
            $warningContent .= '@php // File Generated at: ' . FULL_DATE . ' @endphp' . EOL . EOL;
            File::put($blockPath . DS . $filename . '.blade.php', $warningContent . $content);
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }
    }

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $originalContent = $model->getOriginal('file_contents');
            if (!empty($originalContent) && $model->file_contents != $originalContent) {
                self::regenerateBlock($model->publish, $model->filename, $originalContent, 'backup');
            }
        });

        self::saved(function ($model) {
            if ($model->filename) {
                self::regenerateBlock($model->publish, $model->filename, $model->file_contents);
            }
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(User::class, 'updated_by')->withTrashed();
    }
}
