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
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('faq_type', 30)->nullable()->after('order');
            $table->string('faq_category', 30)->nullable()->after('faq_type');
            
            $table->unsignedBigInteger('product_id')->nullable()->after('faq_category');
            $table->unsignedBigInteger('page_id')->nullable()->after('product_id');

            // Add foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['product_id']);
            $table->dropForeign(['page_id']);

            // Then drop the columns
            $table->dropColumn(['faq_type', 'faq_category', 'product_id', 'page_id']);
        });
    }
};
