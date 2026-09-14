<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    if (view()->exists('home')) {
        return view('home');
    }
    return view('welcome');
});

Route::get('/category/{slug}', function ($slug) {
    $categoryMap = [
        'game' => ['games', 'game', 'voucher'],
        'pulsa' => ['pulsa', 'indosat', 'telkomsel', 'xl', 'tri', 'smartfren', 'axis'],
        'data' => ['data', 'paket data', 'internet'],
        'pln-token' => ['pln', 'token'],
        'pln-bill' => ['pln', 'tagihan'],
        'pdam' => ['pdam', 'air'],
    ];

    $keywords = $categoryMap[$slug] ?? [$slug];
    $products = [];

    if (Schema::hasTable('products')) {
        $query = DB::table('products');
        
        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhereRaw('LOWER(category) LIKE ?', ['%' . strtolower($word) . '%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
            }
        });

        $products = $query->get();
        
        // Jika masih kosong, tampilkan semua produk sebagai fallback
        if ($products->isEmpty()) {
            $products = DB::table('products')->limit(30)->get();
        }
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

Route::get('/login', function() {
    return view('welcome');
})->name('login');
