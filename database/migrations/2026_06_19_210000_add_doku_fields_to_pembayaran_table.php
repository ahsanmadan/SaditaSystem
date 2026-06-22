<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('gateway_provider', 50)
                ->nullable()
                ->after('metode');
            $table->string('gateway_request_id', 100)
                ->nullable()
                ->after('gateway_provider')
                ->index();
            $table->string('gateway_reference', 150)
                ->nullable()
                ->after('gateway_request_id')
                ->index();
            $table->string('gateway_status', 50)
                ->nullable()
                ->after('gateway_reference');
            $table->text('checkout_url')
                ->nullable()
                ->after('gateway_status');
            $table->timestamp('expires_at')
                ->nullable()
                ->after('checkout_url');
            $table->json('gateway_payload')
                ->nullable()
                ->after('expires_at');
            $table->json('gateway_response')
                ->nullable()
                ->after('gateway_payload');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'gateway_provider',
                'gateway_request_id',
                'gateway_reference',
                'gateway_status',
                'checkout_url',
                'expires_at',
                'gateway_payload',
                'gateway_response',
            ]);
        });
    }
};
