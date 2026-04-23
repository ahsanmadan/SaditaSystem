<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->string('nama_pengulas');
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->string('foto_ulasan')->nullable();
            $table->string('token_ulasan', 100)->unique();
            $table->boolean('is_tampil')->default(true)->index();
            $table->timestamps();
            $table->unique(['pesanan_id', 'produk_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('ulasan');
    }
};