<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'currency_id',
        'billing_address_id',
        'shipping_address_id',
        'payment_method',
        'subtotal',
        'total',
        'subtotal',
        'remarks',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function currencies()
    {
        return $this->belongsToMany(Currency::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }


}
