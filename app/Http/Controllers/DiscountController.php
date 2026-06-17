<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Validate and apply discount code.
     */
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|integer|min:0',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $subtotal = (int) $request->input('subtotal');

        $diskon = Diskon::where('kode', $code)->first();

        if (!$diskon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau tidak ditemukan.',
            ]);
        }

        $errorMsg = null;
        if (!$diskon->isValidFor($subtotal, $errorMsg)) {
            return response()->json([
                'success' => false,
                'message' => $errorMsg ?: 'Kode promo tidak dapat digunakan.',
            ]);
        }

        $potongan = $diskon->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'id' => $diskon->id,
            'kode' => $diskon->kode,
            'nama' => $diskon->nama,
            'tipe' => $diskon->tipe,
            'nilai' => $diskon->nilai,
            'potongan' => $potongan,
            'formatted_potongan' => 'Rp ' . number_format($potongan, 0, ',', '.'),
            'grand_total' => $subtotal - $potongan,
            'formatted_grand_total' => 'Rp ' . number_format($subtotal - $potongan, 0, ',', '.'),
        ]);
    }
}
