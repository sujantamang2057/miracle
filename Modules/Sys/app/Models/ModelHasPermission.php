<?php

namespace Modules\Sys\app\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    public $table = 'model_has_permissions';

    public $fillable = [
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'model_type' => 'string',
    ];

    public static array $rules = [
        'model_type' => 'required|string|max:191',
        'model_id' => 'required',
    ];

    public function permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Sys\app\Models\Permission::class, 'permission_id');
    }
}
