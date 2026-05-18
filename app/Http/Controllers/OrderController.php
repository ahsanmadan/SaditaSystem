<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // For Midtrans, prices should be numeric. We strip non-numeric characters.
        $rawPrice = preg_replace('/[^0-9]/', '', $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;

        // Create Order in DB
        $order = Order::create([
            'order_id' => 'SDT-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'product_name' => $request->product_name ?? 'Produk Sadita',
            'jenis' => $request->jenis,
            'price' => $numericPrice,
            'sender_name' => $request->sender_name,
            'sender_phone' => $request->sender_phone,
            'receiver_name' => $request->receiver_name,
            'untuk' => $request->untuk,
            'address' => $request->address,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,
            'greeting_msg' => $request->greeting_msg,
            'special_instruction' => $request->special_instruction,
            'status' => 'UNPAID',
        ]);

        return redirect()->route('invoice.show', ['order_id' => $order->order_id])
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function show($order_id)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
        
        // Midtrans Token logic will be here later
        $snapToken = null;

        return view('pages.home.invoice', compact('order', 'snapToken'));
    }

    public function track($order_id)
    {
        $order = Order::where('order_id', $order_id)->first();
        
        if (!$order) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'order_id' => $order->order_id,
            'product_name' => $order->product_name,
            'status' => $order->status,
            'delivery_date' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '-',
            'delivery_time' => $order->delivery_time ?? '-'
        ]);
    }
}
