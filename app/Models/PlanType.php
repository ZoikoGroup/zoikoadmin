<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PlanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    protected static function booted()
    {
        static::saving(function ($planType) {
            if (!empty($planType->slug)) {
                // Sanitize slug
                $slug = Str::slug($planType->slug);
                $originalSlug = $slug;
                $count = 1;

                // Ensure uniqueness, skip current record
                while (static::where('slug', $slug)
                    ->when($planType->id, fn($q) => $q->where('id', '!=', $planType->id))
                    ->exists()
                ) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }

                $planType->slug = $slug;
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(PlanType::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PlanType::class, 'parent_id');
    }
}
