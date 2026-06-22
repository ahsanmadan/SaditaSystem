<?php

namespace Tests\Feature;

use App\Filament\Resources\Kategoris\KategoriResource;
use App\Filament\Resources\KodePromos\KodePromoResource;
use App\Filament\Resources\Pelanggans\PelangganResource;
use App\Filament\Resources\Pembayarans\PembayaranResource;
use App\Filament\Resources\Pesanans\PesananResource;
use App\Filament\Resources\Produks\ProdukResource;
use App\Filament\Resources\Ulasans\UlasanResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_and_resource_index_pages_can_be_opened(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->actingAs($user);

        $this->get('/admin')->assertOk();

        foreach ($this->resourceClasses() as $resourceClass) {
            $this->get($resourceClass::getUrl('index'))->assertOk();
        }
    }

    /**
     * @return array<int, class-string>
     */
    private function resourceClasses(): array
    {
        return [
            UserResource::class,
            KategoriResource::class,
            ProdukResource::class,
            KodePromoResource::class,
            PelangganResource::class,
            PesananResource::class,
            PembayaranResource::class,
            UlasanResource::class,
        ];
    }
}
