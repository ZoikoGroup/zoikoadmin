<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'price_uk',
        'price_usa',
        'product_category_id',
        'product_discount_type_id',
        'discount',
        'featured',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'product_discount_type_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
