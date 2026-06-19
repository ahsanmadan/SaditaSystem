<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kode_promo', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('tipe_diskon', 20);
            $table->unsignedBigInteger('nilai_diskon');
            $table->unsignedBigInteger('minimum_order')->default(0);
            $table->unsignedInteger('kuota')->nullable();
            $table->unsignedInteger('dipakai')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kode_promo');
    }
};
