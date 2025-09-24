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
            $table->decimal('price', 10, 2)->default(0.00)->after('id');
            $table->string('currency', 10)->default('USD')->after('price');
            $table->enum('duration_type', ['day', 'week', 'month', 'year'])->default('month')->after('currency');
            $table->integer('duration_value')->nullable()->after('duration_type')->comment('Number of units for the selected duration_type');
            $table->json('features')->nullable()->after('duration_value')->comment('Stores product features in JSON format. Example: [{"icon_url": "url", "text": "your text"}]');
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active')->after('features');
            $table->integer('order')->default(0)->after('status');
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
