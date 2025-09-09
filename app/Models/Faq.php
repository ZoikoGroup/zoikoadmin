<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
