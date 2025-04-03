<?php

namespace Modules\Sys\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Common\app\Models\Common;

class EmailTemplate extends Common
{
    use SoftDeletes;

    public $table = 'sys_email_template';

    public $primaryKey = 'template_id';

    public $fillable = [
        'name',
        'mail_code',
        'subject',
        'variables',
        'contents',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'name' => 'string',
        'mail_code' => 'string',
        'subject' => 'string',
        'variables' => 'string',
        'contents' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::saving(function ($model) {
            $originalContent = $model->getOriginal('contents');
            if (!empty($originalContent) && $model->contents != $originalContent) {
                self::regenerateBlock($model->publish, $model->mail_code, $originalContent, 'backup');
            }
        });

        self::saved(function ($model) {
            if ($model->mail_code) {
                self::regenerateBlock($model->publish, $model->mail_code, $model->contents);
            }
        });
    }

    public static function regenerateBlock($publish = 1, $filename = null, $content = null, $mode = '')
    {
        $emailTemplatePath = base_path('resources/views/components/emailTemplates/');
        try {
            if (!File::exists($emailTemplatePath)) {
                File::makeDirectory($emailTemplatePath); // creates directory
            }
            if ($mode == 'backup') {
                $emailTemplatePath .= DS . '_backup_' . date('Hi');

                if (!File::exists($emailTemplatePath)) {
                    File::makeDirectory($emailTemplatePath); // creates directory
                }
            }
            if ($publish == 2) {
                $content = '@php // EmailTemplate Status is OFF, Set ON to show @endphp';
            }
            $warningContent = '@php // DO NOT Modify contents manually, will be REPLACED from backend/Database @endphp' . EOL;
            $warningContent .= '@php // File Generated at: ' . FULL_DATE . ' @endphp' . EOL . EOL;
            File::put($emailTemplatePath . DS . $filename . '.blade.php', $warningContent . $content);
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }
    }

    public static array $messages = [
        'mail_code.regex' => 'Uppercase Alphabets, numbers and hypen sign are only allowed.',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(User::class, 'updated_by')->withTrashed();
    }
}
