<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'order',
        'faq_type',
        'faq_category',
        'product_id',
        'page_id',
    ];

    protected $casts = [
        'faq_category' => 'array', // ✅ ensures JSON cast
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
