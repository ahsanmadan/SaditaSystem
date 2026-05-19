<?php

namespace App\Http\Controllers;

use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua kategori aktif beserta produk aktifnya
        $kategoris = Kategori::where('is_aktif', true)
            ->with(['daftarProduk' => function ($q) {
                $q->where('is_aktif', true)
                  ->orderBy('harga_dasar', 'asc')
                  ->limit(8);
            }])
            ->orderBy('id')
            ->get();

        return view('pages.home.index', compact('kategoris'));
    }
}
