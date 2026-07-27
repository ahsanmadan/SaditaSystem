<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        Mail::fake();

        $owner = User::factory()->create([
            'name' => 'Owner Sadita',
            'email' => 'owner@sadita.test',
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $produk = $this->createProduk('Papan Bunga Dukacita');

        $response = $this->post('/order', [
            'sender_name' => 'Ahsan Ramadan',
            'sender_phone' => '81234567890',
            'sender_email' => 'ahsan@example.test',
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
            'pickup_date' => '2026-07-02',
            'pickup_time' => '13:00',
        ]);

        $pesanan = Pesanan::query()->with(['detailItems', 'pengiriman', 'pelanggan'])->first();

        $response->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]));

        $this->assertDatabaseHas('pelanggan', [
            'id' => $pesanan->pelanggan_id,
            'nama_lengkap' => 'Ahsan Ramadan',
            'no_hp' => '81234567890',
            'email' => 'ahsan@example.test',
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

        Mail::assertSent(\App\Mail\OrderNotification::class, function ($mail) use ($owner, $pesanan) {
            return $mail->hasTo($owner->email) && $mail->order->is($pesanan);
        });

        $this->assertDatabaseHas('email_log', [
            'pesanan_id' => $pesanan->id,
            'email_tujuan' => $owner->email,
            'jenis' => 'pesanan_baru_owner',
            'status' => 'sent',
        ]);

        $this->get(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertOk()
            ->assertSee('Pilih pembayaran', false)
            ->assertSee($pesanan->kode_pesanan, false);

        $this->get(route('invoice.download', ['order_id' => $pesanan->kode_pesanan]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition');
    }

    public function test_guest_order_updates_existing_customer_email_when_phone_is_reused(): void
    {
        $produk = $this->createProduk('Papan Bunga Dukacita');

        $existingPelanggan = Pelanggan::create([
            'nama_lengkap' => 'Pelanggan Lama',
            'no_hp' => '81234567890',
            'email' => null,
        ]);

        $this->post('/order', [
            'sender_name' => 'Ahsan Ramadan',
            'sender_phone' => '81234567890',
            'sender_email' => 'ahsan@example.test',
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
            'pickup_date' => '2026-07-02',
            'pickup_time' => '13:00',
        ])->assertRedirect();

        $existingPelanggan->refresh();

        $this->assertSame('Ahsan Ramadan', $existingPelanggan->nama_lengkap);
        $this->assertSame('ahsan@example.test', $existingPelanggan->email);
        $this->assertDatabaseCount('pelanggan', 1);
    }

    public function test_edit_order_can_switch_to_existing_customer_phone_without_duplicate_phone_error(): void
    {
        $produk = $this->createProduk('Papan Bunga Dukacita');

        $pelangganAwal = Pelanggan::create([
            'nama_lengkap' => 'Pelanggan Awal',
            'no_hp' => '81111111111',
            'email' => 'awal@example.test',
        ]);

        $pelangganTarget = Pelanggan::create([
            'nama_lengkap' => 'Pelanggan Target',
            'no_hp' => '82222222222',
            'email' => null,
        ]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelangganAwal->id,
            'kode_pesanan' => 'SDT-EDIT-001',
            'status' => Pesanan::STATUS_MENUNGGU,
            'tipe_layanan' => 'papan_bunga',
            'total_harga' => 250000,
            'subtotal_produk_jasa' => 250000,
            'estimasi_belanja' => 0,
            'realisasi_belanja' => 0,
            'biaya_tambahan' => 0,
            'biaya_ongkir' => 0,
            'diskon' => 0,
            'grand_total' => 250000,
            'total_dibayar' => 0,
            'sisa_tagihan' => 250000,
            'batas_waktu_bayar' => now()->addDay(),
        ]);

        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => 'Penerima Awal',
            'no_hp_penerima' => '81111111111',
            'alamat_lengkap' => 'Alamat Awal',
            'tanggal_pengiriman' => now()->toDateString(),
            'jam_pengiriman' => '09:00:00',
            'status' => 'menunggu_jadwal',
        ]);

        $this->post('/order', [
            'order_id' => $pesanan->kode_pesanan,
            'sender_name' => 'Ahsan Ramadan',
            'sender_phone' => '82222222222',
            'sender_email' => 'ahsan@example.test',
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
            'pickup_date' => '2026-07-02',
            'pickup_time' => '13:00',
        ])->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]));

        $pesanan->refresh();
        $pelangganTarget->refresh();

        $this->assertSame($pelangganTarget->id, $pesanan->pelanggan_id);
        $this->assertSame('Ahsan Ramadan', $pelangganTarget->nama_lengkap);
        $this->assertSame('ahsan@example.test', $pelangganTarget->email);
        $this->assertDatabaseCount('pelanggan', 2);
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
                'track_url' => route('tracking.page', ['code' => $pesanan->kode_pesanan]),
            ]);
    }

    public function test_tracking_returns_not_found_for_unknown_code(): void
    {
        $this->getJson(route('order.track', ['order_id' => 'SDT-TEST-KOSONG']))
            ->assertNotFound()
            ->assertJson(['found' => false]);
    }

    public function test_tracking_page_shows_order_data_when_code_exists(): void
    {
        $pesanan = $this->createOrderWithRelations();

        $this->get(route('tracking.page', ['code' => $pesanan->kode_pesanan]))
            ->assertOk()
            ->assertSee('Lacak pesanan', false)
            ->assertSee($pesanan->kode_pesanan, false)
            ->assertSee('Lanjut bayar', false);
    }

    public function test_tracking_page_shows_not_found_state_when_code_is_unknown(): void
    {
        $this->get(route('tracking.page', ['code' => 'SDT-TIDAK-ADA']))
            ->assertOk()
            ->assertSee('Kode tidak ditemukan', false);
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

    public function test_inactive_promo_cannot_be_applied(): void
    {
        $pesanan = $this->createOrderWithRelations(totalHarga: 250000);

        KodePromo::create([
            'kode' => 'OFFPROMO',
            'tipe_diskon' => 'nominal',
            'nilai_diskon' => 25000,
            'minimum_order' => 100000,
            'kuota' => 5,
            'dipakai' => 0,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_berakhir' => now()->addDay()->toDateString(),
            'is_aktif' => false,
            'deskripsi' => 'Promo nonaktif.',
        ]);

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('order.apply-promo', ['order_id' => $pesanan->kode_pesanan]), [
                'promo_code' => 'OFFPROMO',
            ])
            ->assertRedirect(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->assertSessionHasErrors(['promo_code']);
    }

    public function test_promo_respects_minimum_order_rule(): void
    {
        $pesanan = $this->createOrderWithRelations(totalHarga: 150000);

        KodePromo::create([
            'kode' => 'MIN200',
            'tipe_diskon' => 'persentase',
            'nilai_diskon' => 10,
            'minimum_order' => 200000,
            'kuota' => 5,
            'dipakai' => 0,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_berakhir' => now()->addDay()->toDateString(),
            'is_aktif' => true,
            'deskripsi' => 'Promo minimal 200rb.',
        ]);

        $this->from(route('invoice.show', ['order_id' => $pesanan->kode_pesanan]))
            ->post(route('order.apply-promo', ['order_id' => $pesanan->kode_pesanan]), [
                'promo_code' => 'MIN200',
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
