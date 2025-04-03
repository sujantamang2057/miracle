<?php

namespace Modules\Common\app\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\app\Models\Traits\HasSchemaAccessors;

class Common extends Model
{
    use HasSchemaAccessors;

    public static array $rules = [];

    public static function boot(): void
    {

        parent::boot();

        self::creating(function ($model) {
            if (self::schemaHasColumn('created_by')) {
                $model->created_by = Auth::id();
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
                $model->updated_by = Auth::id();
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

        // check if model has SoftDelete columns or not
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(self::class))) {
            self::trashed(function ($model) {
                if (self::schemaHasColumn('deleted_at')) {
                    $model->deleted_at = NOW;
                }
                if (self::schemaHasColumn('deleted_by')) {
                    $model->deleted_by = Auth::id();
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
        }

        self::replicating(function ($model) {
            // ... code here
        });
    }

    public function hasAttribute($attr)
    {
        return self::schemaHasColumn($attr);
    }

    public function generateSlug($title, $slug, $slugField = '')
    {
        $slugField = empty($slugField) ? 'slug' : $slugField;
        $slug = empty($slug) ? $title : $slug;
        $uniqueSlug = $slug = generateSeoLinks($slug);
        $iteration = 0;
        while ($this->validateSlug($uniqueSlug, $slugField)) {
            $iteration++;
            $uniqueSlug = $this->generateUniqueSlug($slug, $iteration);
        }
        $this->$slugField = $uniqueSlug;
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('publish', 1);
    }

    protected function generateUniqueSlug($baseSlug, $iteration)
    {
        return $baseSlug . '-' . ($iteration + 1);
    }

    protected function validateSlug($slug, $slugField)
    {
        $tblName = $this->table;
        $primaryKey = $this->primaryKey;
        $rules = $this::$rules;

        $slugRule = $rules[$slugField] ?? 'unique:' . $tblName . ',' . $slugField;
        if ($this->$primaryKey) {
            $slugRule .= ',' . $this->$primaryKey . ',' . $primaryKey;
        }
        $validation = validator(
            [$slugField => $slug],
            [$slugField => $slugRule],
            [$slugField => __('common::messages.slug_already_taken')],
        );

        return $validation->fails();
    }

    /**
     * Get the purgable model query.
     */
    public function scopePurgable(Builder $query, $subMonths = 12)
    {
        if (self::schemaHasColumn('deleted_at')) {
            return $query->where('deleted_at', '<=', now()->subMonth($subMonths));
        }

        return $query;
    }
}
