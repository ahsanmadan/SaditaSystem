<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->cascadeOnDelete();
            $table->string('nama_penerima', 150);
            $table->string('no_hp_penerima', 20);
            $table->text('alamat_lengkap');
            $table->string('patokan_lokasi')->nullable();
            $table->date('tanggal_pengiriman')->index();
            $table->time('jam_pengiriman');
            $table->string('nama_kurir')->nullable();
            $table->string('status', 50)->default('menunggu_jadwal')->index();
            $table->timestamp('waktu_terkirim')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pengiriman');
    }
};