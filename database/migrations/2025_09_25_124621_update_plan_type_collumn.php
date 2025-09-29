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
            // Change plan_type to a plain integer (nullable)
            $table->integer('plan_type')->nullable()->change();

            // Add bq_id after id
            $table->string('bq_id', 50)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Drop the column if rollback
            $table->dropColumn('bq_id');

            // (Optional) revert plan_type back to previous type if known
            // Example: $table->string('plan_type')->change();
        });
    }
};
