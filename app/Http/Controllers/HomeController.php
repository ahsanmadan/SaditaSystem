<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $kategoris = $this->fetchActiveCategories(8);

        return view('pages.home.index', compact('kategoris'));
    }

    public function catalog(Request $request)
    {
        $kategoris = $this->fetchActiveCategories();
        $bestSellers = $this->fetchBestSellers();
        $selectedCategory = Str::slug((string) $request->query('category', 'all'));
        $availableCategoryKeys = $kategoris->pluck('catalog_key')->all();

        if ($selectedCategory !== 'all' && !in_array($selectedCategory, $availableCategoryKeys, true)) {
            $selectedCategory = 'all';
        }

        $visibleKategoris = $kategoris;

        return view('pages.home.catalog', compact('kategoris', 'visibleKategoris', 'selectedCategory', 'bestSellers'));
    }

    private function fetchActiveCategories(?int $productLimit = null)
    {
        return Kategori::where('is_aktif', true)
            ->with(['daftarProduk' => function ($query) {
                $query->where('is_aktif', true)
                    ->with('kategori:id,slug')
                    ->with('gambarItems:id,produk_id,path_gambar,is_utama')
                    ->orderBy('harga_dasar', 'asc');
            }])
            ->orderBy('id')
            ->get()
            ->map(function ($kategori) use ($productLimit) {
                $produk = $productLimit ? $kategori->daftarProduk->take($productLimit) : $kategori->daftarProduk;

                $kategori->setRelation('daftarProduk', $produk->values());
                $kategori->catalog_key = $this->resolveCatalogCategoryKey($kategori);
                $kategori->catalog_label = $this->resolveCatalogCategoryLabel($kategori);

                return $kategori;
            })
            ->groupBy('catalog_key')
            ->map(function ($items, $catalogKey) use ($productLimit) {
                $base = $items->first();
                $mergedProducts = $items
                    ->flatMap(fn ($kategori) => $kategori->daftarProduk)
                    ->unique('id')
                    ->sortBy('harga_dasar')
                    ->values();

                if ($productLimit) {
                    $mergedProducts = $mergedProducts->take($productLimit)->values();
                }

                $base->nama = $base->catalog_label;
                $base->catalog_key = $catalogKey;
                $base->setRelation('daftarProduk', $mergedProducts);

                return $base;
            })
            ->values();
    }

    private function resolveCatalogCategoryKey(Kategori $kategori): string
    {
        $slug = Str::slug($kategori->slug ?: $kategori->nama);

        if (in_array($slug, ['papan-bunga', 'papan-ucapan'], true)) {
            return 'papan-bunga-ucapan';
        }

        return $slug;
    }

    private function resolveCatalogCategoryLabel(Kategori $kategori): string
    {
        $slug = Str::slug($kategori->slug ?: $kategori->nama);

        if (in_array($slug, ['papan-bunga', 'papan-ucapan'], true)) {
            return 'Papan Bunga/Ucapan';
        }

        return $kategori->nama;
    }

    private function fetchBestSellers(int $limit = 4)
    {
        $stats = DetailPesanan::query()
            ->select('detail_pesanan.produk_id', DB::raw('SUM(detail_pesanan.kuantitas) as total_terjual'))
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->where('pesanan.status', Pesanan::STATUS_SELESAI)
            ->groupBy('detail_pesanan.produk_id')
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();

        if ($stats->isEmpty()) {
            return collect();
        }

        $orderedIds = $stats->pluck('produk_id')->all();
        $totalsByProduct = $stats->pluck('total_terjual', 'produk_id');

        return Produk::query()
            ->where('is_aktif', true)
            ->whereIn('id', $orderedIds)
            ->with('kategori:id,slug,nama')
            ->with('gambarItems:id,produk_id,path_gambar,is_utama')
            ->get()
            ->sortBy(fn ($produk) => array_search($produk->id, $orderedIds, true))
            ->values()
            ->map(function ($produk) use ($totalsByProduct) {
                $produk->best_seller_total = (int) ($totalsByProduct[$produk->id] ?? 0);

                return $produk;
            });
    }
}
