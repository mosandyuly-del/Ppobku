<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/category/{slug}', function ($slug) {
    $products = collect();

    if (Schema::hasTable('products')) {
        $products = DB::table('products')->where('status', 'active')->limit(30)->get();
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

Route::get('/cek-pesanan', function () {
    return view('welcome');
});
