<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Checkout & Pesanan
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');

// Rute Kategori/Layanan Standard
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);

// Fallback Route (Semua URL selain di atas dialihkan ke halaman produk)
Route::get('/{slug}', [HomeController::class, 'category'])->where('slug', '[A-Za-z0-9\-_]+');
