<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengembalian_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->cascadeOnDelete();
            $table->date('tanggal_jemput')->nullable();
            $table->string('status_pengembalian', 50)->default('menunggu_dijemput')->index();
            $table->string('kondisi_barang', 50)->nullable();
            $table->text('catatan_kerusakan')->nullable();
            $table->string('foto_bukti_kerusakan')->nullable();
            $table->unsignedBigInteger('denda_kerusakan')->default(0);
            $table->string('status_denda', 50)->default('tidak_ada');
            $table->timestamp('waktu_dijemput')->nullable();
            $table->timestamps();
            $table->index(['pesanan_id', 'status_denda']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('pengembalian_pesanan');
    }
};