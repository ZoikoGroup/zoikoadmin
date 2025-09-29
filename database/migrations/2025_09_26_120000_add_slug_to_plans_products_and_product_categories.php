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
        // Add slug to plans
        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug', 255)->after('title')->unique()->nullable();
        });

        // Add slug to products
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug', 255)->after('name')->unique()->nullable();
        });

        // Add slug to product_categories
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('slug', 255)->after('name')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
