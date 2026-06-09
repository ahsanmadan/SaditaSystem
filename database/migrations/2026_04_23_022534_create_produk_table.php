<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('nama', 200);
            $table->string('slug', 200)->unique();
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga_dasar');
            $table->boolean('is_customizable')->default(false);
            $table->boolean('is_sewa')->default(false);
            $table->boolean('is_aktif')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
