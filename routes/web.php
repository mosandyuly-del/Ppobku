<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

// Beranda & Kategori Layanan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);

// Checkout & Lacak Pesanan
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');
