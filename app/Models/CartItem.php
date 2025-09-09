<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

}
