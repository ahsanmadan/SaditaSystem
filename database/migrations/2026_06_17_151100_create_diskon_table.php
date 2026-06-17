<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 100);
            $table->string('tipe', 20)->default('nominal'); // 'persen' atau 'nominal'
            $table->unsignedBigInteger('nilai');
            $table->unsignedBigInteger('minimal_pembelian')->default(0);
            $table->unsignedBigInteger('maksimal_potongan')->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('digunakan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_berakhir')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('diskon');
    }
};
