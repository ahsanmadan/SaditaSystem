<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('foto_utama')->nullable()->after('deskripsi')
                ->comment('Path foto utama produk, tersimpan di storage/app/public/produk/foto');
            $table->json('galeri_foto')->nullable()->after('foto_utama')
                ->comment('Array path foto galeri tambahan, disimpan sebagai JSON');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['foto_utama', 'galeri_foto']);
        });
    }
};
