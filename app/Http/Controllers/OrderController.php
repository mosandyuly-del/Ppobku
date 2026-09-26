<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request, $id)
    {
        // Cari produk berdasarkan ID atau SKU
        $product = DB::table('products')->where('id', $id)->orWhere('buyer_sku_code', $id)->first();

        if (!$product) {
            return redirect('/')->with('error', 'Produk tidak ditemukan.');
        }

        $phone = $request->query('phone', '');

        return view('checkout', [
            'product' => $product,
            'phone' => $phone
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'phone' => 'required',
            'payment_method' => 'required'
        ]);

        $product = DB::table('products')->where('id', $request->product_id)->first();
        $trx_id = 'TRX-' . strtoupper(uniqid());

        // Simpan transaksi sederhana
        DB::table('orders')->insert([
            'trx_id' => $trx_id,
            'product_name' => $product->name ?? 'Produk Digital',
            'phone' => $request->phone,
            'price' => $product->price_sell ?? $product->price ?? 0,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/cek-pesanan?trx_id=' . $trx_id);
    }

    public function checkStatus(Request $request)
    {
        $trx_id = $request->query('trx_id');
        $order = null;

        if ($trx_id) {
            $order = DB::table('orders')->where('trx_id', $trx_id)->first();
        }

        return view('check_status', ['order' => $order]);
    }
}
