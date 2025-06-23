<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'menu_id',
        'url',
        'target',
        'css_class',
        'css_id',
        'item_order',
    ];
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
