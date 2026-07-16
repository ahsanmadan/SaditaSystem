<?php

namespace Tests\Feature;

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderQuickActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_advance_regular_order_without_opening_edit_form(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $order = $this->makeOrder(false, Pesanan::STATUS_DIPROSES);

        $this->actingAs($admin)
            ->post(route('admin.orders.quick-action', $order), [
                'action' => 'advance',
            ])
            ->assertRedirect();

        $this->assertSame(Pesanan::STATUS_SIAPKIRIM, $order->fresh()->status);

        $this->actingAs($admin)
            ->post(route('admin.orders.quick-action', $order), [
                'action' => 'advance',
            ])
            ->assertRedirect();

        $order->refresh();

        $this->assertSame(Pesanan::STATUS_SELESAI, $order->status);
        $this->assertNull($order->pickup_deadline_at);
    }

    public function test_rental_order_can_enter_pickup_and_finish_without_denda(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $order = $this->makeOrder(true, Pesanan::STATUS_DIPROSES);

        $this->actingAs($admin)->post(route('admin.orders.quick-action', $order), ['action' => 'advance']);
        $this->actingAs($admin)->post(route('admin.orders.quick-action', $order), ['action' => 'advance']);

        $order->refresh();

        $this->assertSame(Pesanan::STATUS_SELESAI, $order->status);
        $this->assertNotNull($order->pickup_deadline_at);

        $order->forceFill([
            'status' => Pesanan::STATUS_SELESAI,
            'pickup_deadline_at' => now()->addMinutes(10),
        ])->save();

        $this->actingAs($admin)
            ->post(route('admin.orders.quick-action', $order), [
                'action' => 'pickup_ok',
            ])
            ->assertRedirect();

        $order->refresh();

        $this->assertSame(Pesanan::STATUS_SELESAI, $order->status);
        $this->assertNull($order->pickup_deadline_at);
        $this->assertSame('baik', $order->pengembalian()->first()?->kondisi_barang);
        $this->assertSame('tidak_ada', $order->pengembalian()->first()?->status_denda);
    }

    public function test_rental_order_can_move_to_denda_and_close_after_payment(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $order = $this->makeOrder(true, Pesanan::STATUS_SELESAI);
        $order->forceFill([
            'pickup_deadline_at' => now()->addMinutes(10),
        ])->save();

        $this->actingAs($admin)
            ->post(route('admin.orders.quick-action', $order), [
                'action' => 'pickup_damaged',
            ])
            ->assertRedirect();

        $order->refresh();

        $this->assertSame(Pesanan::STATUS_MENUNGGU_DENDA, $order->status);
        $this->assertSame('rusak', $order->pengembalian()->first()?->kondisi_barang);
        $this->assertSame('menunggu_pembayaran', $order->pengembalian()->first()?->status_denda);

        $this->actingAs($admin)
            ->post(route('admin.orders.quick-action', $order), [
                'action' => 'damage_paid',
            ])
            ->assertRedirect();

        $order->refresh();

        $this->assertSame(Pesanan::STATUS_SELESAI, $order->status);
        $this->assertSame('lunas', $order->pengembalian()->first()?->status_denda);
    }

    private function makeOrder(bool $isRental, string $status): Pesanan
    {
        $pelanggan = Pelanggan::factory()->create();
        $kategori = Kategori::query()->create([
            'nama' => $isRental ? 'Papan Bunga' : 'Dekorasi',
            'slug' => $isRental ? 'papan-bunga' : 'dekorasi',
            'deskripsi' => 'Kategori test',
            'is_aktif' => true,
        ]);

        $produk = Produk::query()->create([
            'kategori_id' => $kategori->id,
            'nama' => $isRental ? 'Sewa Standing Board' : 'Dekorasi Intimate',
            'slug' => $isRental ? 'sewa-standing-board' : 'dekorasi-intimate',
            'deskripsi' => 'Produk test',
            'harga_dasar' => 600000,
            'is_customizable' => true,
            'is_sewa' => $isRental,
            'is_aktif' => true,
        ]);

        $order = Pesanan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => 'SDT-TEST-'.str()->upper(str()->random(6)),
            'status' => $status,
            'tipe_layanan' => $isRental ? 'sewa' : 'jasa',
            'total_harga' => 600000,
            'biaya_ongkir' => 0,
            'grand_total' => 600000,
        ]);

        DetailPesanan::factory()->create([
            'pesanan_id' => $order->id,
            'produk_id' => $produk->id,
            'nama_produk_snapshot' => $produk->nama,
            'harga_satuan_snapshot' => 600000,
            'kuantitas' => 1,
            'subtotal' => 600000,
        ]);

        return $order->fresh(['detailItems.produk', 'pengembalian']);
    }
}
