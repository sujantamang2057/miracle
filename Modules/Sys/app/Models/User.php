<?php

namespace Modules\Sys\app\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Common\app\Models\Traits\HasSchemaAccessors;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasSchemaAccessors, Notifiable, SoftDeletes;

    public $table = 'users';

    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'active',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static array $rules = [
        'name' => 'required|string|between:3,50',
        'email' => 'required|email:rfc,dns|string|between:10,100|unique:users,email',
        'profile_image' => 'nullable|string|max:100',
        'active' => 'required|integer|min:1|max:2',
    ];

    // Added Functions

    public function hasAttribute($attr)
    {
        return self::schemaHasColumn($attr);
    }

    public static function boot(): void
    {

        parent::boot();

        self::creating(function ($model) {
            if (self::schemaHasColumn('created_by')) {
                $model->created_by = !empty($model->created_by) ?: Auth::id();
            }
            if (self::schemaHasColumn('show_order')) {
                $model->show_order = self::max('show_order') + 1;
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            if (self::schemaHasColumn('updated_by')) {
                $model->updated_by = !empty($model->updated_by) ?: Auth::id();
            }
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::saving(function ($model) {
            if (self::schemaHasColumn('updated_at') && empty($model->getKey())) {
                $model->updated_at = null;
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });

        self::softDeleted(function ($model) {
            if (self::schemaHasColumn('deleted_at')) {
                $model->deleted_at = NOW;
            }
            if (self::schemaHasColumn('deleted_by')) {
                $model->deleted_by = !empty($model->deleted_by) ?: Auth::id();
            }
        });

        self::forceDeleted(function ($model) {
            // ... code here
        });

        self::restoring(function ($model) {
            // ... code here
        });

        self::restored(function ($model) {
            // ... code here
        });

        self::replicating(function ($model) {
            // ... code here
        });
    }

    /**
     * Get field value by id
     */
    public static function getSingleFieldData($userId = null, $returnField = 'name')
    {
        $returnValue = null;
        if (empty($userId)) {
            return $returnValue;
        }
        $select = "$returnField AS data";
        $search['id'] = $userId;
        $dbData = self::select($select)
            ->where($search)
            ->limit(1)
            ->get();
        foreach ($dbData as $key => $value) {
            $returnValue = isset($value['data']) ? $value['data'] : null;
        }

        return $returnValue;
    }

    public function getRoleIdAttribute()
    {
        $firstRole = $this->roles()->first();

        return !empty($firstRole) ? $firstRole->id : '';
    }

    public function getRoleNameAttribute()
    {
        $firstRole = $this->roles()->first();

        return !empty($firstRole) ? $firstRole->name : '';
    }
}
