<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class OrderControllerWhiteBoxTest extends TestCase
{
    #[DataProvider('deliveryTimeProvider')]
    public function test_normalize_delivery_time_covers_all_branches(?string $input, string $expected): void
    {
        $controller = new OrderController();
        $method = new ReflectionMethod(OrderController::class, 'normalizeDeliveryTime');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($controller, $input));
    }

    public static function deliveryTimeProvider(): array
    {
        return [
            'kosong fallback ke default' => [null, '09:00:00'],
            'label waktu dipetakan ke slot internal' => ['Pagi (08:00 - 12:00)', '09:00:00'],
            'jam format hh:mm ditambah detik' => ['14:30', '14:30:00'],
            'jam format hh:mm:ss dipertahankan' => ['14:30:15', '14:30:15'],
            'format tidak valid fallback ke default' => ['malam nanti', '09:00:00'],
        ];
    }
}
