<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPesananFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_hantaran_titip_belanja_pesanan(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        // Create initial order
        $kategori = Kategori::create([
            'nama' => 'Hantaran',
            'slug' => 'hantaran',
            'is_aktif' => true,
        ]);

        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Set Hantaran Premium',
            'slug' => 'set-hantaran-premium',
            'harga_dasar' => 300000,
            'tipe_layanan' => 'hantaran',
            'is_customizable' => true,
            'is_sewa' => true,
            'is_aktif' => true,
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Budi Santoso',
            'no_hp' => '81234567890',
        ]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => 'SDT-TEST-ADMIN',
            'tipe_layanan' => 'hantaran',
            'mode_hantaran' => 'titip_belanja',
            'catatan_belanja' => 'Beli barang mewah',
            'status' => Pesanan::STATUS_MENUNGGU,
            'total_harga' => 300000,
            'subtotal_produk_jasa' => 300000,
            'estimasi_belanja' => 0,
            'realisasi_belanja' => 0,
            'biaya_tambahan' => 0,
            'biaya_ongkir' => 15000,
            'diskon' => 0,
            'grand_total' => 315000,
            'total_dibayar' => 0,
            'sisa_tagihan' => 315000,
        ]);

        // Admin updates the order with estimated shopping budget and additional charges
        $response = $this->put(route('admin.update', ['focus' => 'pesanan', 'record' => $pesanan->id]), [
            'pelanggan_id' => $pelanggan->id,
            'kode_promo_id' => null,
            'kode_pesanan' => $pesanan->kode_pesanan,
            'tipe_layanan' => 'hantaran',
            'mode_hantaran' => 'titip_belanja',
            'catatan_belanja' => 'Beli barang mewah updated',
            'status' => Pesanan::STATUS_DIPROSES,
            'total_harga' => 300000,
            'subtotal_produk_jasa' => 300000,
            'estimasi_belanja' => 450000, // Estimasi belanja diisi admin
            'realisasi_belanja' => 0,
            'biaya_tambahan' => 10000, // Biaya tambahan
            'biaya_ongkir' => 15000,
            'diskon' => 0,
            'grand_total' => 775000, // 300k + 450k + 10k + 15k
            'total_dibayar' => 200000, // DP dibayarkan
            'sisa_tagihan' => 575000,
            'batas_waktu_bayar' => now()->addDay()->format('Y-m-d H:i:s'),
            'catatan_pembeli' => 'Catatan admin',
        ]);

        $response->assertRedirect();

        // Refresh and check database
        $pesanan->refresh();

        $this->assertEquals('titip_belanja', $pesanan->mode_hantaran);
        $this->assertEquals('Beli barang mewah updated', $pesanan->catatan_belanja);
        $this->assertEquals(450000, $pesanan->estimasi_belanja);
        $this->assertEquals(10000, $pesanan->biaya_tambahan);
        $this->assertEquals(200000, $pesanan->total_dibayar);
        // Calculation: 300k (subtotal) + 450k (estimasi) + 10k (biaya_tambahan) + 15k (ongkir) = 775k
        $this->assertEquals(775000, $pesanan->grand_total);
        // Calculation: 775k - 200k (dibayar) = 575k
        $this->assertEquals(575000, $pesanan->sisa_tagihan);
    }
}
