<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'admin', 'staff'])
                ->default('staff')
                ->after('is_admin')
                ->comment('owner=full, admin=operasional, staff=read-only');
        });

        // Migrate existing is_admin=true users → role='owner'
        DB::table('users')->where('is_admin', true)->update(['role' => 'owner']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
