<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //use HasFactory;
    protected $fillable = ['title', 'content', 'og_title', 'og_type', 'og_description', 'meta_title', 'meta_keywords', 'meta_description', 'created_at', 'updated_at'];
}
