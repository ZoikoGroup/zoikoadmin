<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->bigInteger('billing_address_id')->unsigned()->nullable();
            $table->bigInteger('shipping_address_id')->unsigned()->nullable();
            $table->string('payment_method')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('total')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
