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
        Schema::create('kode_promo', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('tipe_diskon', 20); // 'persentase' or 'nominal'
            $table->unsignedBigInteger('nilai_diskon');
            $table->unsignedBigInteger('minimum_order')->default(0);
            $table->integer('kuota')->nullable();
            $table->integer('dipakai')->default(0);
            $table->timestamp('berlaku_sampai')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_promo');
    }
};
