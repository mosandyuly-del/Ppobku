<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

// 1. Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Rute Checkout & Pembayaran
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/pembayaran/{trx_id}', [OrderController::class, 'payment'])->name('payment');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');

// 3. Rute Standar Kategori & Layanan
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);
Route::get('/layanan', [HomeController::class, 'category']);

// 4. CATCH-ALL ROUTE (Mencegah Error 404 untuk semua klik menu/tombol)
Route::get('/{slug}', [HomeController::class, 'category'])->where('slug', '[A-Za-z0-9\-_]+');
