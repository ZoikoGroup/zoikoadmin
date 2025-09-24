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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_type', ['digital', 'physical'])->default('physical')->after('product_category_id');
            $table->string('image_url')->nullable()->after('product_type');
            $table->unsignedBigInteger('plan_id')->nullable()->after('image_url');

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['product_type', 'image_url', 'plan_id']);
        });
    }
};
