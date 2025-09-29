<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'slug', // ✅ added slug
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
     * Auto-generate slug when creating.
     */
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });
    }

    /**
     * Generate a unique slug.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Relationships
     */
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'product_discount_type_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
