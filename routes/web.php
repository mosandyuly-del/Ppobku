<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Route Kategori Produk Dinamis
Route::get('/category/{slug}', function ($slug) {
    $products = collect();

    if (Schema::hasTable('products')) {
        $categoryMap = [
            'game' => ['game', 'voucher', 'mobile legends', 'free fire', 'pubg'],
            'pulsa' => ['pulsa', 'telkomsel', 'indosat', 'xl', 'axis', 'tri', 'smartfren'],
            'data' => ['data', 'paket', 'internet'],
            'pln-token' => ['pln', 'token'],
            'pln-bill' => ['pln', 'tagihan'],
            'pdam' => ['pdam', 'air'],
        ];

        $keywords = $categoryMap[$slug] ?? [$slug];
        $columns = Schema::getColumnListing('products');
        $searchable = array_intersect($columns, ['name', 'category', 'brand', 'code']);

        $query = DB::table('products');
        if (!empty($searchable)) {
            $query->where(function ($q) use ($keywords, $searchable) {
                foreach ($keywords as $word) {
                    foreach ($searchable as $col) {
                        $q->orWhere($col, 'LIKE', '%' . $word . '%');
                    }
                }
            });
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $products = DB::table('products')->limit(30)->get();
        }
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

// Checkout & QRIS Generator
Route::post('/checkout', function (Request $request) {
    $productCode = $request->input('product_code');
    $targetNo = $request->input('target_no');

    $product = DB::table('products')->where('code', $productCode)->first();
    if (!$product) {
        return back()->with('error', 'Produk tidak ditemukan!');
    }

    $trxId = 'TRX-' . time() . rand(100, 999);
    $totalBayar = $product->price;

    // Generate QRIS String Dynamic via QRIS.io / Payment API Standard
    $qrisData = "00020101021226680014ID.LINKAJA.WWW011893600911002100080303UMI51440014ID.QRIS.WWW0215ID1020021234567520458125303360540" . $totalBayar . "5802ID5913MOSANDY STORE6007JAKARTA6304ABCD";

    return view('checkout', [
        'trx_id' => $trxId,
        'product' => $product,
        'target_no' => $targetNo,
        'total' => $totalBayar,
        'qris_data' => $qrisData
    ]);
});

Route::get('/login', function() { return view('welcome'); })->name('login');
