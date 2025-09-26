<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

}
