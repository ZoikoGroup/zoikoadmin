<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'bq_id',
        'plan_type_id',
        'plan_type',
        'title',
        'slug',
        'sub_title',
        'tag',
        'price',
        'currency',
        'duration_type',
        'duration_value',
        'features',
        'status',
        'order',
        'image_url',
        'meta_title',
        'meta_slug',
        'meta_description',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    public function planType()
    {
        return $this->belongsTo(PlanType::class, 'plan_type_id');
    }

    public function getFeaturesListAttribute()
    {
        if (!is_array($this->features)) {
            return [];
        }

        return collect($this->features)->map(fn($feature) => $feature['text'] ?? '')->toArray();
    }
}
