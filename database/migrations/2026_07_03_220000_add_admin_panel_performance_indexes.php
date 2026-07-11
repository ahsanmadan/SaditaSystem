<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->index('created_at', 'idx_pelanggan_created_at');
            $table->index('nama_lengkap', 'idx_pelanggan_nama_lengkap');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->index('created_at', 'idx_pesanan_created_at');
            $table->index(['pelanggan_id', 'created_at'], 'idx_pesanan_pelanggan_created');
            $table->index(['status', 'batas_waktu_bayar'], 'idx_pesanan_status_batas_bayar');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index(['pesanan_id', 'created_at'], 'idx_pembayaran_pesanan_created');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropIndex('idx_pembayaran_pesanan_created');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropIndex('idx_pesanan_created_at');
            $table->dropIndex('idx_pesanan_pelanggan_created');
            $table->dropIndex('idx_pesanan_status_batas_bayar');
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropIndex('idx_pelanggan_created_at');
            $table->dropIndex('idx_pelanggan_nama_lengkap');
        });
    }
};
