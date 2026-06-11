<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\PengembalianPesanan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Ulasan;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $totalPesanan = env('SEEDER_STRESS_MODE', false) ? 1500 : 300;

        // Create 100 Pelanggan
        $pelangganList = Pelanggan::factory(100)->create();
        $repeatCustomers = $pelangganList->random(20); // 20% repeat >3 times

        $produks = Produk::all();
        $admin = User::first() ?? User::factory()->create();

        // Distribusi Status
        $selesaiCount = (int) ($totalPesanan * 0.55);
        $diprosesCount = (int) ($totalPesanan * 0.20);
        $menungguCount = (int) ($totalPesanan * 0.15);
        $dibatalkanCount = $totalPesanan - ($selesaiCount + $diprosesCount + $menungguCount);

        $statuses = array_merge(
            array_fill(0, $selesaiCount, 'selesai'),
            array_fill(0, $diprosesCount, 'diproses'),
            array_fill(0, $menungguCount, 'menunggu_pembayaran'),
            array_fill(0, $dibatalkanCount, 'dibatalkan')
        );
        shuffle($statuses);

        foreach ($statuses as $i => $status) {
            // Seasonality Date Logic (Last 90 days, weight on weekends)
            $isWeekend = $faker->boolean(60); // 60% chance to happen on weekend
            $daysAgo = $faker->numberBetween(1, 90);
            $date = Carbon::now()->subDays($daysAgo);
            if ($isWeekend) {
                while (! $date->isWeekend()) {
                    $date->addDay();
                    if ($date->isFuture()) {
                        $date->subDays(7);
                    }
                }
            }

            // Customer Assignment (Repeat order logic)
            $pelanggan = $faker->boolean(40) ? $repeatCustomers->random() : $pelangganList->random();

            $ongkir = $faker->randomElement([50000, 100000, 150000, 0]);

            $pesanan = Pesanan::create([
                'pelanggan_id' => $pelanggan->id,
                'kode_pesanan' => 'ORD-'.$date->format('Ymd').'-'.strtoupper(Str::random(5)),
                'status' => $status,
                'total_harga' => 0,
                'biaya_ongkir' => $ongkir,
                'grand_total' => 0,
                'batas_waktu_bayar' => $date->copy()->addHours(24),
                'waktu_selesai' => ($status === 'selesai') ? $date->copy()->addDays(2) : null,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Add Details
            $numItems = $faker->numberBetween(1, 3);
            $pickedProducts = $produks->random($numItems);
            $totalHarga = 0;
            $hasSewa = false;

            foreach ($pickedProducts as $prod) {
                $qty = $faker->numberBetween(1, 2);
                $sub = $prod->harga_dasar * $qty;
                $totalHarga += $sub;
                if ($prod->is_sewa) {
                    $hasSewa = true;
                }

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $prod->id,
                    'nama_produk_snapshot' => $prod->nama,
                    'harga_satuan_snapshot' => $prod->harga_dasar,
                    'kuantitas' => $qty,
                    'subtotal' => $sub,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $pesanan->update([
                'total_harga' => $totalHarga,
                'grand_total' => $totalHarga + $ongkir,
            ]);

            // Workflow Generation based on Status
            if (in_array($status, ['diproses', 'selesai'])) {
                Pembayaran::factory()->create([
                    'pesanan_id' => $pesanan->id,
                    'jumlah_dibayar' => $pesanan->grand_total,
                    'status' => 'lunas',
                    'diverifikasi_oleh' => $admin->id,
                    'waktu_dibayar' => $date->copy()->addHours(1),
                    'waktu_diverifikasi' => $date->copy()->addHours(2),
                    'created_at' => $date->copy()->addHours(1),
                ]);
            } elseif ($status === 'dibatalkan' && $faker->boolean(20)) {
                Pembayaran::factory()->create([
                    'pesanan_id' => $pesanan->id,
                    'jumlah_dibayar' => $pesanan->grand_total,
                    'status' => 'ditolak',
                    'alasan_penolakan' => 'Bukti transfer palsu/buram',
                    'waktu_dibayar' => $date->copy()->addHours(1),
                    'created_at' => $date->copy()->addHours(1),
                ]);
            }

            if ($status === 'selesai') {
                Pengiriman::factory()->create([
                    'pesanan_id' => $pesanan->id,
                    'tanggal_pengiriman' => $date->copy()->addDays(1)->format('Y-m-d'),
                    'waktu_terkirim' => $date->copy()->addDays(1)->addHours(5),
                    'created_at' => $date,
                ]);

                if ($hasSewa) {
                    PengembalianPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'tanggal_jemput' => $date->copy()->addDays(2),
                        'status_pengembalian' => 'selesai',
                        'kondisi_barang' => $faker->boolean(90) ? 'aman' : 'ada_kerusakan',
                        'waktu_dijemput' => $date->copy()->addDays(2)->addHours(4),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                if ($faker->boolean(70)) {
                    foreach ($pickedProducts as $prod) {
                        Ulasan::factory()->create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $prod->id,
                            'created_at' => $date->copy()->addDays(3),
                        ]);
                    }
                }
            }
        }
    }
}
