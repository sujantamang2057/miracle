<?php

namespace Modules\Sys\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Common\app\Models\Traits\HasSchemaAccessors;

class Role extends Model
{
    use HasSchemaAccessors;

    public $table = 'roles';

    public $fillable = [
        'name',
        'guard_name',
    ];

    protected $casts = [
        'name' => 'string',
        'guard_name' => 'string',
    ];

    public static array $rules = [
        'name' => 'required|string|max:191|unique:roles',
        'guard_name' => 'required|string|max:191',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    public function modelHasRole(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\Modules\Sys\app\Models\ModelHasRole::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\Modules\Sys\app\Models\Permission::class, 'role_has_permissions');
    }

    public function permissionCount(): int
    {
        return $this->permissions()
            ->whereNot(function ($query) {
                $query->where('name', 'like', '%.*');
            })
            ->count();
    }

    public function hasAttribute($attr)
    {
        return self::schemaHasColumn($attr);
    }

    public static function getFilteredRoleList($selected = '', $addExtraOption = true)
    {
        $roles = self::select(['id', 'name'])->orderBy('id')->get();
        $loggedInUserRole = auth()->user()->role_name;
        $roleOrderList = ROLES_ORDER_LIST;
        $roleKey = array_search($loggedInUserRole, $roleOrderList);
        $excludeRolesArr = [];
        // remove higher roles
        foreach ($roleOrderList as $key => $orderRole) {
            if ($key < $roleKey) {
                $excludeRolesArr[] = $orderRole;
            }
        }
        // Formatting the roles data
        $formattedRoles = $roles->map(function ($item) use ($selected, $excludeRolesArr) {
            if (in_array($item->name, $excludeRolesArr)) {
                return null;
            }
            $data = [
                'id' => $item->id,
                'text' => $item->name,
            ];
            if (!empty($selected) && ($item->id == $selected)) {
                $data['selected'] = true;
            }

            return $data;
        })->toArray();
        $formattedRoles = array_values(Arr::whereNotNull($formattedRoles));

        return $addExtraOption ? array_merge([['id' => '', 'text' => __('common::crud.text.select_any')]], $formattedRoles) : $formattedRoles;
    }
}
