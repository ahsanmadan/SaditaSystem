<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->string('nama_produk_snapshot');
            $table->unsignedBigInteger('harga_satuan_snapshot');
            $table->integer('kuantitas');
            $table->unsignedBigInteger('subtotal');
            $table->text('teks_ucapan')->nullable();
            $table->string('referensi_desain')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('detail_pesanan');
    }
};