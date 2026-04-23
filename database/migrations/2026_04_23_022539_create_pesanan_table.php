<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->restrictOnDelete();
            $table->string('kode_pesanan', 50)->unique();
            $table->string('status', 50)->default('menunggu_pembayaran');
            $table->unsignedBigInteger('total_harga');
            $table->unsignedBigInteger('biaya_ongkir')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->timestamp('batas_waktu_bayar')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->text('catatan_pembeli')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('pesanan');
    }
};