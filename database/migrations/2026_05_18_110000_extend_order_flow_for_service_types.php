<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('tipe_layanan', 30)
                ->nullable()
                ->after('harga_dasar')
                ->index();
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('tipe_layanan', 30)
                ->nullable()
                ->after('kode_pesanan')
                ->index();
            $table->string('mode_hantaran', 30)
                ->nullable()
                ->after('tipe_layanan');
            $table->unsignedBigInteger('subtotal_produk_jasa')
                ->default(0)
                ->after('status');
            $table->unsignedBigInteger('estimasi_belanja')
                ->default(0)
                ->after('subtotal_produk_jasa');
            $table->unsignedBigInteger('realisasi_belanja')
                ->default(0)
                ->after('estimasi_belanja');
            $table->unsignedBigInteger('biaya_tambahan')
                ->default(0)
                ->after('realisasi_belanja');
            $table->unsignedBigInteger('total_dibayar')
                ->default(0)
                ->after('grand_total');
            $table->unsignedBigInteger('sisa_tagihan')
                ->default(0)
                ->after('total_dibayar');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('jenis_tagihan', 30)
                ->default('pelunasan')
                ->after('metode')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('jenis_tagihan');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'tipe_layanan',
                'mode_hantaran',
                'subtotal_produk_jasa',
                'estimasi_belanja',
                'realisasi_belanja',
                'biaya_tambahan',
                'total_dibayar',
                'sisa_tagihan',
            ]);
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('tipe_layanan');
        });
    }
};
