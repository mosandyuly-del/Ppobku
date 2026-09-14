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
        'game' => 'Games',
        'pulsa' => 'Pulsa',
        'data' => 'Data',
        'pln-token' => 'PLN',
        'pln-bill' => 'PLN',
        'pdam' => 'PDAM',
    ];

    $categoryName = $categoryMap[$slug] ?? $slug;
    $products = [];

    if (Schema::hasTable('products')) {
        $products = DB::table('products')
            ->where('category', 'LIKE', '%' . $categoryName . '%')
            ->get();
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
