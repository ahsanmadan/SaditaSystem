<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_can_be_opened(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sadita', false);

        $this->get('/order?product=Papan%20Bunga&price=Rp%20250.000')
            ->assertOk()
            ->assertSee('Isi detail pesanan tanpa ribet.', false);
    }

    public function test_guest_can_create_order_and_view_invoice(): void
    {
        $produk = $this->createProduk('Papan Bunga Dukacita');

        $response = $this->post('/order', [
            'sender_name' => 'Ahsan Ramadan',
            'sender_phone' => '81234567890',
            'receiver_name' => 'Bagatio Putra',
            'untuk' => 'PT Sadita Abadi',
            'address' => 'Jl. Mawar No. 10, Pekanbaru',
            'delivery_date' => '2026-06-30',
            'delivery_time' => '09:00:00',
            'greeting_msg' => 'Turut berduka cita.',
            'special_instruction' => 'Hubungi satpam sebelum masuk.',
            'product_name' => $produk->nama,
            'price' => 'Rp 250.000',
            'jenis' => 'Sewa',
        ]);

        $pesanan = Pesanan::query()->with(['detailItems', 'pengiriman', 'pelanggan'])->first();

        $response->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]));

        $this->assertDatabaseHas('pelanggan', [
            'id' => $pesanan->pelanggan_id,
            'nama_lengkap' => 'Ahsan Ramadan',
            'no_hp' => '81234567890',
        ]);

        $this->assertDatabaseHas('detail_pesanan', [
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'nama_produk_snapshot' => 'Papan Bunga Dukacita',
            'subtotal' => 250000,
        ]);

        $this->assertDatabaseHas('pengiriman', [
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => 'Bagatio Putra',
            'alamat_lengkap' => 'Jl. Mawar No. 10, Pekanbaru',
            'jam_pengiriman' => '09:00:00',
        ]);

        $this->get(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertOk()
            ->assertSee('Pilih pembayaran', false)
            ->assertSee($pesanan->kode_pesanan, false);
    }

    public function test_tracking_returns_created_order_data(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $this->getJson(route('order.track', ['order_id' => $pesanan->kode_pesanan]))
            ->assertOk()
            ->assertJson([
                'found' => true,
                'order_id' => $pesanan->kode_pesanan,
                'product_name' => 'Papan Bunga Premium',
                'status' => 'UNPAID',
            ]);
    }

    public function test_tracking_returns_not_found_for_unknown_code(): void
    {
        $this->getJson(route('order.track', ['order_id' => 'SDT-TEST-KOSONG']))
            ->assertNotFound()
            ->assertJson(['found' => false]);
    }

    public function test_valid_promo_updates_grand_total_and_blank_code_clears_it(): void
    {
        $pesanan = $this->createOrderWithRelations(totalHarga: 250000);

        $promo = KodePromo::create([
            'kode' => 'SADITA10',
            'tipe_diskon' => 'persentase',
            'nilai_diskon' => 10,
            'minimum_order' => 100000,
            'kuota' => 20,
            'dipakai' => 0,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_berakhir' => now()->addDay()->toDateString(),
            'is_aktif' => true,
            'deskripsi' => 'Promo diskon 10 persen.',
        ]);

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('order.apply-promo', ['order_id' => $pesanan->kode_pesanan]), [
                'promo_code' => 'SADITA10',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHas('success');

        $pesanan->refresh();

        $this->assertSame($promo->id, $pesanan->kode_promo_id);
        $this->assertSame('SADITA10', $pesanan->kode_promo_snapshot);
        $this->assertSame(25000, $pesanan->diskon);
        $this->assertSame(225000, $pesanan->grand_total);

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('order.apply-promo', ['order_id' => $pesanan->kode_pesanan]), [
                'promo_code' => '',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHas('success');

        $pesanan->refresh();

        $this->assertNull($pesanan->kode_promo_id);
        $this->assertNull($pesanan->kode_promo_snapshot);
        $this->assertSame(0, $pesanan->diskon);
        $this->assertSame(250000, $pesanan->grand_total);
    }

    public function test_invalid_promo_returns_error_message(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('order.apply-promo', ['order_id' => $pesanan->kode_pesanan]), [
                'promo_code' => 'SALAH123',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHasErrors(['promo_code']);
    }

    private function createOrderWithRelations(int $totalHarga = 150000): Pesanan
    {
        $produk = $this->createProduk('Papan Bunga Premium');

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Pelanggan Sadita',
            'no_hp' => '81200001111',
            'email' => 'pelanggan@example.test',
        ]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => 'SDT-20260622-ABCDE',
            'status' => Pesanan::STATUS_MENUNGGU,
            'total_harga' => $totalHarga,
            'biaya_ongkir' => 0,
            'diskon' => 0,
            'grand_total' => $totalHarga,
            'batas_waktu_bayar' => now()->addDay(),
            'catatan_pembeli' => 'Catatan pengujian',
        ]);

        $pesanan->detailItems()->create([
            'produk_id' => $produk->id,
            'nama_produk_snapshot' => $produk->nama,
            'harga_satuan_snapshot' => $totalHarga,
            'kuantitas' => 1,
            'subtotal' => $totalHarga,
            'teks_ucapan' => 'Pesan uji',
            'referensi_desain' => null,
        ]);

        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => 'Penerima Sadita',
            'no_hp_penerima' => '81200002222',
            'alamat_lengkap' => 'Jl. Anggrek No. 99, Pekanbaru',
            'patokan_lokasi' => 'Gedung utama',
            'tanggal_pengiriman' => '2026-06-30',
            'jam_pengiriman' => '13:00:00',
            'status' => 'menunggu_jadwal',
        ]);

        return $pesanan;
    }

    private function createProduk(string $nama): Produk
    {
        $kategori = Kategori::create([
            'nama' => 'Papan Bunga',
            'slug' => 'papan-bunga',
            'deskripsi' => 'Kategori untuk pengujian otomatis.',
            'is_aktif' => true,
        ]);

        return Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => $nama,
            'slug' => strtolower(str_replace(' ', '-', $nama)).'-'.fake()->unique()->numerify('###'),
            'deskripsi' => 'Produk dummy untuk pengujian.',
            'harga_dasar' => 250000,
            'is_customizable' => true,
            'is_sewa' => true,
            'is_aktif' => true,
        ]);
    }
}
