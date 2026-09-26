<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;

// Rute Publik Beranda & Layanan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);

// Rute Checkout & Cek Pesanan
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');
Route::get('/cek-ip', [HomeController::class, 'checkIp'])->name('check.ip');

// Rute Admin Panel
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
