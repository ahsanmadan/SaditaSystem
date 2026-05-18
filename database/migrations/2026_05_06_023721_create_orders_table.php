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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // e.g., SDT-20260411-001
            $table->string('product_name');
            $table->string('jenis')->nullable(); // Standing Full, dll
            $table->string('price');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('receiver_name');
            $table->string('untuk')->nullable(); // PT ABC, dll
            $table->text('address');
            $table->date('delivery_date');
            $table->string('delivery_time');
            $table->text('greeting_msg')->nullable();
            $table->string('special_instruction')->nullable();
            $table->string('status')->default('UNPAID'); // UNPAID, PAID
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
