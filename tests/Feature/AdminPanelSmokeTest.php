<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_lite_routes_can_be_opened_via_custom_admin_surface(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->actingAs($user);

        foreach ($this->adminLitePaths() as $path) {
            $this->followingRedirects()
                ->get($path)
                ->assertOk();
        }
    }

    /**
     * @return array<int, string>
     */
    private function adminLitePaths(): array
    {
        return [
            '/admin-lite',
            '/admin/search?q=owner',
            '/admin-lite/kategori/manage',
            '/admin-lite/kategori/create',
            '/admin-lite/produk/manage',
            '/admin-lite/produk/create',
            '/admin-lite/promo/manage',
            '/admin-lite/promo/create',
            '/admin-lite/pelanggan/manage',
            '/admin-lite/pelanggan/create',
            '/admin-lite/ulasan/manage',
            '/admin-lite/pesanan/manage',
            '/admin-lite/pesanan/create',
            '/admin-lite/pembayaran/manage',
            '/admin-lite/pembayaran/create',
            '/admin-lite/users/manage',
            '/admin-lite/users/create',
        ];
    }
}
