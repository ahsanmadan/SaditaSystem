<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('kode_promo_id')
                ->nullable()
                ->after('pelanggan_id')
                ->constrained('kode_promo')
                ->nullOnDelete();

            $table->unsignedBigInteger('diskon')
                ->default(0)
                ->after('biaya_ongkir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['kode_promo_id']);
            $table->dropColumn(['kode_promo_id', 'diskon']);
        });
    }
};
