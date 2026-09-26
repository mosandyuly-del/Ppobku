<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

// Halaman Utama & Layanan Kategori
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);

// Checkout & Pembayaran QRIS
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/pembayaran/{trx_id}', [OrderController::class, 'payment'])->name('payment');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');
