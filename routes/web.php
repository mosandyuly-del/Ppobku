<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

// Rute Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Layanan / Kategori
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);
Route::get('/layanan', [HomeController::class, 'category']);

// Rute Order & Checkout
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/pembayaran/{trx_id}', [OrderController::class, 'payment'])->name('payment');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');

// Fallback Rute Dinamis untuk mencegah 404
Route::get('/{slug}', [HomeController::class, 'category'])->where('slug', '[A-Za-z0-9\-_]+');
