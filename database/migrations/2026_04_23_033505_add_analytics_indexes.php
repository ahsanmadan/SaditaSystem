<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->index(['status', 'waktu_selesai'], 'idx_pesanan_status_waktu');
            $table->index(['pelanggan_id', 'status'], 'idx_pesanan_pelanggan_status');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index(['status', 'waktu_dibayar'], 'idx_pembayaran_status_waktu');
        });

        Schema::table('pengeluaran_pesanan', function (Blueprint $table) {
            $table->index('pesanan_id', 'idx_pengeluaran_pesanan_id');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropIndex('idx_pesanan_status_waktu');
            $table->dropIndex('idx_pesanan_pelanggan_status');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropIndex('idx_pembayaran_status_waktu');
        });

        Schema::table('pengeluaran_pesanan', function (Blueprint $table) {
            $table->dropIndex('idx_pengeluaran_pesanan_id');
        });
    }
};
