<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request, $trx_id)
    {
        $product = DB::table('products')->where('id', $trx_id)->orWhere('buyer_sku_code', $trx_id)->first();

        if (!$product) {
            return redirect('/')->with('error', 'Produk tidak ditemukan.');
        }

        return view('checkout', [
            'product' => $product,
            'phone' => $request->query('phone', '')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'phone' => 'required'
        ]);

        $product = DB::table('products')->where('id', $request->product_id)->first();
        $trx_id = 'TRX-' . strtoupper(uniqid());

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
        $order = $trx_id ? DB::table('orders')->where('trx_id', $trx_id)->first() : null;

        return view('check_status', ['order' => $order]);
    }
}
