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
        $columns = Schema::getColumnListing('products');
        $query = DB::table('products');

        // Peta pencarian berdasarkan slug
        $categoryMap = [
            'game' => ['game', 'voucher', 'mobile legends', 'free fire'],
            'pulsa' => ['pulsa', 'telkomsel', 'indosat', 'xl', 'axis', 'tri', 'smartfren'],
            'data' => ['data', 'paket', 'internet'],
            'pln-token' => ['pln', 'token'],
            'pln-bill' => ['pln', 'tagihan'],
            'pdam' => ['pdam', 'air'],
        ];

        $keywords = $categoryMap[$slug] ?? [$slug];

        // Cari kolom yang berpotensi menyimpan nama/kategori
        $searchableColumns = array_intersect($columns, ['name', 'nama', 'title', 'brand', 'kategori', 'type', 'group', 'code']);

        if (!empty($searchableColumns)) {
            $query->where(function ($q) use ($keywords, $searchableColumns) {
                foreach ($keywords as $word) {
                    foreach ($searchableColumns as $col) {
                        $q->orWhere($col, 'LIKE', '%' . $word . '%');
                    }
                }
            });
        }

        $products = $query->get();

        // Fallback: Jika tidak ada yang cocok, tampilkan semua produk yang ada di tabel
        if ($products->isEmpty()) {
            $products = DB::table('products')->limit(50)->get();
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
