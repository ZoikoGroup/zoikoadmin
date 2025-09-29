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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('updated_at');
            $table->string('meta_title')->nullable()->after('image_url');
            $table->string('meta_slug')->nullable()->after('meta_title');
            $table->string('meta_description')->nullable()->after('meta_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
