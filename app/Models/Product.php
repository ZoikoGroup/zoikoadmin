<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_category_id',
        'product_type',
        'image_url',
        'plan_id',
        'product_discount_type_id',
        'name',
        'description',
        'short_description',
        'price_uk',
        'price_usa',
        'discount',
        'featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price_uk' => 'decimal:2',
        'price_usa' => 'decimal:2',
        'discount' => 'decimal:2',
        'featured' => 'boolean',
    ];

    /**
     * Get the category this product belongs to.
     */
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Get the discount type this product uses.
     */
    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'product_discount_type_id');
    }

    /**
     * Get the plan associated with this product.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Get the attributes for this product.
     */
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
