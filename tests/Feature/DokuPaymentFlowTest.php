<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\Payments\DokuCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DokuPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_checkout_rejects_invalid_payment_method(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('doku.checkout', ['order_id' => $pesanan->kode_pesanan]), [
                'payment_method' => 'METODE_TIDAK_VALID',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHas('error');
    }

    public function test_checkout_shows_error_when_doku_is_not_configured(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $mock = Mockery::mock(DokuCheckoutService::class);
        $mock->shouldReceive('isConfigured')->once()->andReturn(false);
        $this->app->instance(DokuCheckoutService::class, $mock);

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('doku.checkout', ['order_id' => $pesanan->kode_pesanan]), [
                'payment_method' => 'ALL',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHas('error');
    }

    public function test_refresh_status_shows_error_when_no_doku_payment_exists(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $this->post(route('doku.refresh', ['order_id' => $pesanan->kode_pesanan]))
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHas('error');
    }

    public function test_notification_rejects_invalid_signature(): void
    {
        $mock = Mockery::mock(DokuCheckoutService::class);
        $mock->shouldReceive('verifyNotificationSignature')->once()->andReturn(false);
        $this->app->instance(DokuCheckoutService::class, $mock);

        $this->postJson(route('doku.notify'), [
            'order' => [
                'invoice_number' => 'SDT-20260622-ABCDE',
            ],
        ])
            ->assertStatus(401)
            ->assertJson([
                'responseCode' => '401',
                'responseMessage' => 'Invalid signature',
            ]);
    }

    public function test_notification_stores_actual_doku_channel_as_payment_method(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $pesanan = $this->createOrderWithRelations();

        $mock = Mockery::mock(DokuCheckoutService::class);
        $mock->shouldReceive('verifyNotificationSignature')->once()->andReturn(true);
        $this->app->instance(DokuCheckoutService::class, $mock);

        $this->postJson(route('doku.notify'), [
            'order' => [
                'invoice_number' => $pesanan->kode_pesanan,
                'amount' => 200000,
            ],
            'transaction' => [
                'status' => 'SUCCESS',
            ],
            'channel' => [
                'id' => 'VIRTUAL_ACCOUNT_BCA',
            ],
            'acquirer' => [
                'id' => 'BCA',
            ],
        ], [
            'Signature' => 'dummy',
            'Client-Id' => 'dummy',
            'Request-Id' => 'dummy',
            'Request-Timestamp' => now()->toIso8601String(),
        ])->assertOk();

        $payment = Pembayaran::query()->where('pesanan_id', $pesanan->id)->first();

        $this->assertNotNull($payment);
        $this->assertSame('VIRTUAL_ACCOUNT_BCA', $payment->metode);
        $this->assertSame(Pembayaran::GATEWAY_DOKU, $payment->gateway_provider);
        $this->assertSame(Pembayaran::STATUS_LUNAS, $payment->status);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\OrderNotification::class, function ($mail) use ($pesanan) {
            return $mail->hasTo(config('mail.admin_address')) && $mail->pesanan->id === $pesanan->id;
        });
    }

    private function createOrderWithRelations(): Pesanan
    {
        $kategori = Kategori::create([
            'nama' => 'Papan Bunga',
            'slug' => 'papan-bunga',
            'deskripsi' => 'Kategori untuk pengujian.',
            'is_aktif' => true,
        ]);

        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Papan Bunga Doku',
            'slug' => 'papan-bunga-doku',
            'deskripsi' => 'Produk untuk uji DOKU.',
            'harga_dasar' => 200000,
            'is_customizable' => true,
            'is_sewa' => false,
            'is_aktif' => true,
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Pelanggan DOKU',
            'no_hp' => '81222223333',
            'email' => 'doku@example.test',
        ]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => 'SDT-DOKU-12345',
            'status' => Pesanan::STATUS_MENUNGGU,
            'total_harga' => 200000,
            'biaya_ongkir' => 0,
            'diskon' => 0,
            'grand_total' => 200000,
            'batas_waktu_bayar' => now()->addDay(),
            'catatan_pembeli' => 'Pesanan untuk uji DOKU.',
        ]);

        $pesanan->detailItems()->create([
            'produk_id' => $produk->id,
            'nama_produk_snapshot' => $produk->nama,
            'harga_satuan_snapshot' => 200000,
            'kuantitas' => 1,
            'subtotal' => 200000,
            'teks_ucapan' => null,
            'referensi_desain' => null,
        ]);

        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => 'Penerima DOKU',
            'no_hp_penerima' => '81244445555',
            'alamat_lengkap' => 'Jl. Melati No. 8, Pekanbaru',
            'patokan_lokasi' => 'Sebelah minimarket',
            'tanggal_pengiriman' => '2026-06-30',
            'jam_pengiriman' => '17:00:00',
            'status' => 'menunggu_jadwal',
        ]);

        return $pesanan;
    }
}
