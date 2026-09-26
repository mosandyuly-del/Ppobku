<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if (!$product) {
            return redirect('/')->with('error', 'Produk tidak ditemukan');
        }

        return view('checkout', [
            'product' => $product,
            'phone'   => request('phone', '')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'phone'      => 'required|numeric'
        ]);

        $product = DB::table('products')->where('id', $request->product_id)->first();
        if (!$product) {
            return back()->with('error', 'Produk tidak valid.');
        }

        $trxId = 'MS-' . date('YmdHis') . rand(10, 99);

        $orderId = DB::table('orders')->insertGetId([
            'trx_id'       => $trxId,
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'product_code' => $product->buyer_sku_code ?? 'PPOB',
            'phone'        => $request->phone,
            'price'        => $product->price_sell,
            'status'       => 'Pending',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('payment.show', $trxId);
    }

    public function showPayment($trxId)
    {
        $order = DB::table('orders')->where('trx_id', $trxId)->first();
        if (!$order) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Format Pesan WhatsApp Konfirmasi ke Admin 08777480215
        $adminWa = '628777480215';
        $msg = "Halo Admin MOSANDY STORE, saya ingin konfirmasi pembayaran QRIS:\n\n"
             . "📌 *Kode Trx:* {$order->trx_id}\n"
             . "📲 *No. Tujuan:* {$order->phone}\n"
             . "📦 *Produk:* {$order->product_name}\n"
             . "💰 *Total Bayar:* Rp " . number_format($order->price, 0, ',', '.') . "\n\n"
             . "Mohon dikonfirmasi dan diproses. Terima kasih!";

        $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($msg);

        return view('payment', [
            'order' => $order,
            'waUrl' => $waUrl
        ]);
    }

    public function checkStatus(Request $request)
    {
        $trxId = $request->query('trx_id');
        $order = null;

        if ($trxId) {
            $order = DB::table('orders')->where('trx_id', $trxId)->orWhere('phone', $trxId)->orderBy('id', 'desc')->first();
        }

        return view('check_status', [
            'order' => $order,
            'search' => $trxId
        ]);
    }
}
