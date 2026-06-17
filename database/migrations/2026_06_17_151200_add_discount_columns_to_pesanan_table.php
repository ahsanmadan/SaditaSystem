<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('diskon_id')->nullable()->after('pelanggan_id')->constrained('diskon')->nullOnDelete();
            $table->string('kode_diskon', 50)->nullable()->after('biaya_ongkir');
            $table->unsignedBigInteger('potongan_diskon')->default(0)->after('kode_diskon');
        });
    }

    public function down(): void {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['diskon_id']);
            $table->dropColumn(['diskon_id', 'kode_diskon', 'potongan_diskon']);
        });
    }
};
