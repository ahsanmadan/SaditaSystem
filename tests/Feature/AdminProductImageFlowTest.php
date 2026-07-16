<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductImageFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_store_and_update_product_images_into_gallery_table(): void
    {
        $owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $kategori = Kategori::create([
            'nama' => 'Hantaran',
            'slug' => 'hantaran',
            'deskripsi' => 'Kategori uji.',
            'is_aktif' => true,
        ]);

        $this->actingAs($owner);

        $this->post(route('admin.store', ['focus' => 'produk']), [
            'kategori_id' => $kategori->id,
            'nama' => 'Box Premium',
            'slug' => 'box-premium',
            'deskripsi' => 'Produk uji gambar.',
            'harga_dasar' => 350000,
            'foto_utama' => 'images/produk/utama.jpg',
            'galeri_foto' => "images/produk/utama.jpg\nimages/produk/detail-1.jpg\nimages/produk/detail-2.jpg",
            'is_customizable' => true,
            'is_sewa' => false,
            'is_aktif' => true,
        ])->assertRedirect(route('admin.index', ['focus' => 'produk', 'mode' => 'manage']));

        $product = Produk::query()->where('slug', 'box-premium')->firstOrFail();

        $this->assertSame([
            'images/produk/utama.jpg',
            'images/produk/detail-1.jpg',
            'images/produk/detail-2.jpg',
        ], $product->galeri_foto);

        $this->assertDatabaseCount('gambar_produk', 3);
        $this->assertDatabaseHas('gambar_produk', [
            'produk_id' => $product->id,
            'path_gambar' => 'images/produk/utama.jpg',
            'is_utama' => true,
        ]);

        $this->put(route('admin.update', ['focus' => 'produk', 'record' => $product->id]), [
            'kategori_id' => $kategori->id,
            'nama' => 'Box Premium Update',
            'slug' => 'box-premium',
            'deskripsi' => 'Produk uji gambar update.',
            'harga_dasar' => 375000,
            'foto_utama' => 'images/produk/detail-1.jpg',
            'galeri_foto' => "images/produk/detail-1.jpg\nimages/produk/detail-3.jpg",
            'is_customizable' => true,
            'is_sewa' => false,
            'is_aktif' => true,
        ])->assertRedirect(route('admin.index', ['focus' => 'produk', 'mode' => 'manage']));

        $product->refresh();

        $this->assertSame([
            'images/produk/detail-1.jpg',
            'images/produk/detail-3.jpg',
        ], $product->galeri_foto);

        $this->assertDatabaseCount('gambar_produk', 2);
        $this->assertDatabaseHas('gambar_produk', [
            'produk_id' => $product->id,
            'path_gambar' => 'images/produk/detail-1.jpg',
            'is_utama' => true,
        ]);
        $this->assertDatabaseMissing('gambar_produk', [
            'produk_id' => $product->id,
            'path_gambar' => 'images/produk/utama.jpg',
        ]);
    }
}
