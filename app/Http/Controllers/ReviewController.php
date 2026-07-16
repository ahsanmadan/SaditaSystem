<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function show(string $token): View
    {
        $review = Ulasan::with(['pesanan.pelanggan', 'produk.kategori'])
            ->where('token_ulasan', $token)
            ->firstOrFail();

        return view('pages.home.review', [
            'review' => $review,
            'isUsed' => $this->isUsed($review),
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $review = Ulasan::with(['pesanan.pelanggan', 'produk.kategori'])
            ->where('token_ulasan', $token)
            ->firstOrFail();

        if ($this->isUsed($review)) {
            return redirect()
                ->route('review.show', ['token' => $token])
                ->with('info', 'Link ulasan ini sudah pernah dipakai.');
        }

        $validated = $request->validate([
            'nama_pengulas' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['nullable', 'string', 'max:5000'],
        ]);

        $review->forceFill([
            'nama_pengulas' => $validated['nama_pengulas'],
            'rating' => (int) $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
            'is_tampil' => true,
            'used_at' => now(),
        ])->save();

        return redirect()
            ->route('review.show', ['token' => $token])
            ->with('success', 'Terima kasih, ulasan Anda sudah berhasil dikirim.');
    }

    private function isUsed(Ulasan $review): bool
    {
        return $review->used_at !== null || (int) $review->rating > 0;
    }
}
