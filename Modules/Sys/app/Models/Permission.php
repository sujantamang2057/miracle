<?php

namespace Modules\Sys\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\app\Models\Traits\HasSchemaAccessors;

class Permission extends Model
{
    use HasSchemaAccessors;

    public $table = 'permissions';

    public $fillable = [
        'name',
        'guard_name',
    ];

    protected $casts = [
        'name' => 'string',
        'guard_name' => 'string',
    ];

    public static array $rules = [
        'name' => 'required|string|max:191|unique:permissions,name',
        'guard_name' => 'required|string|max:191',
    ];

    public function modelHasPermission(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\Modules\Sys\app\Models\ModelHasPermission::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\Modules\Sys\app\Models\Role::class, 'role_has_permissions');
    }

    public function hasAttribute($attr)
    {
        return self::schemaHasColumn($attr);
    }
}
