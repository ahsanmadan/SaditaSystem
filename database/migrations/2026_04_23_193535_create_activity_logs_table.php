<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50)->comment('verifikasi_pembayaran, tolak_pembayaran, ubah_status_pesanan, moderasi_ulasan, edit_produk_harga, dll');
            $table->string('subject_type', 100)->nullable()->comment('Model class, e.g. App\\Models\\Pembayaran');
            $table->unsignedBigInteger('subject_id')->nullable()->comment('ID record yang diaksi');
            $table->json('payload')->nullable()->comment('Data kontekstual: before/after, alasan, dll');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
