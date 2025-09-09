<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'price',
        'currency',
        'duration_type',
        'duration_value',
        'features',
        'status',
        'order',
    ];

    protected $casts = [
        'features' => 'array',
        'price'    => 'decimal:2',
    ];

    /**
     * Scope for prepaid plans.
     */
    public function scopePrepaid($query)
    {
        return $query->where('plan_type', 'prepaid');
    }

    /**
     * Scope for postpaid plans.
     */
    public function scopePostpaid($query)
    {
        return $query->where('plan_type', 'postpaid');
    }
}
