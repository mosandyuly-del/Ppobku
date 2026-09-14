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
    $products = collect();

    if (Schema::hasTable('products')) {
        // Jika database masih kosong, jalankan sync darurat di tempat
        if (DB::table('products')->count() == 0) {
            \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
        }

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

        // Fallback jika tidak ada filter yang cocok: tampilkan seluruh produk yang tersedia
        if ($products->isEmpty()) {
            $products = DB::table('products')->get();
        }
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

Route::get('/login', function() { return view('welcome'); })->name('login');
