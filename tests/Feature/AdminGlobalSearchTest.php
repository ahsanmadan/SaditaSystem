<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_across_core_entities(): void
    {
        $owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $kategori = Kategori::query()->create([
            'nama' => 'Marun Signature',
            'slug' => 'marun-signature',
            'is_aktif' => true,
        ]);

        $produk = Produk::query()->create([
            'kategori_id' => $kategori->id,
            'nama' => 'Sepatu Marun',
            'slug' => 'sepatu-marun',
            'harga_dasar' => 125000,
            'is_customizable' => false,
            'is_sewa' => false,
            'is_aktif' => true,
        ]);

        $pelanggan = Pelanggan::factory()->create([
            'nama_lengkap' => 'Ahsan Ramadan',
            'email' => 'ahsan@example.test',
            'no_hp' => '081234567890',
        ]);

        $pesanan = Pesanan::query()->create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => 'SDT-20260630-SEHOW',
            'status' => Pesanan::STATUS_MENUNGGU,
            'total_harga' => 150000,
            'biaya_ongkir' => 0,
            'grand_total' => 150000,
        ]);

        $this->actingAs($owner)
            ->getJson(route('admin.search.global', ['q' => 'Marun']))
            ->assertOk()
            ->assertJsonPath('query', 'Marun')
            ->assertJsonPath('total', 2)
            ->assertJsonFragment([
                'key' => 'kategori',
                'label' => 'Kategori',
            ])
            ->assertJsonFragment([
                'title' => $kategori->nama,
                'badge' => 'Kategori',
                'url' => route('admin.edit', ['focus' => 'kategori', 'record' => $kategori->id]),
            ])
            ->assertJsonFragment([
                'title' => $produk->nama,
                'badge' => 'Produk',
                'url' => route('admin.edit', ['focus' => 'produk', 'record' => $produk->id]),
            ]);

        $this->actingAs($owner)
            ->getJson(route('admin.search.global', ['q' => 'Ahsan']))
            ->assertOk()
            ->assertJsonFragment([
                'title' => $pelanggan->nama_lengkap,
                'badge' => 'Pelanggan',
            ]);

        $this->actingAs($owner)
            ->getJson(route('admin.search.global', ['q' => 'SDT-20260630']))
            ->assertOk()
            ->assertJsonFragment([
                'title' => $pesanan->kode_pesanan,
                'badge' => 'Pesanan',
            ]);

        $this->actingAs($owner)
            ->get(route('admin.search', ['q' => 'Ahsan']))
            ->assertOk()
            ->assertSee('Hasil pencarian global')
            ->assertSee('Ahsan Ramadan')
            ->assertSee('Kelola pelanggan');
    }
}
