<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian_pesanan', function (Blueprint $table) {
            $table->date('tanggal_pengambilan')->nullable()->after('tanggal_jemput');
            $table->time('jam_pengambilan')->nullable()->after('tanggal_pengambilan');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian_pesanan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pengambilan', 'jam_pengambilan']);
        });
    }
};
