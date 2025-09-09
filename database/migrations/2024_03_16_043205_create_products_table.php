<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_category_id')->unsigned()->nullable();
            //$table->integer('product_type_id')->unsigned()->nullable();
            $table->integer('product_discount_type_id')->unsigned()->nullable();
            $table->string('name')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price_uk', 8, 2);
            $table->decimal('price_usa', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
