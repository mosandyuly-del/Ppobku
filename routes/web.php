<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;

// Rute Publik Beranda & Layanan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/layanan/{slug}', [HomeController::class, 'category']);

// Rute Checkout, Pembayaran, & Cek Pesanan
Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/payment/{trxId}', [OrderController::class, 'showPayment'])->name('payment.show');
Route::get('/cek-pesanan', [OrderController::class, 'checkStatus'])->name('check.status');
Route::get('/cek-ip', [HomeController::class, 'checkIp'])->name('check.ip');

// Rute Admin Panel
Route::get('/admin', function() {
    return redirect()->route('admin.login');
});
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login-page', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);

// Rute Dashboard Admin & Tembak Orderan
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/orders/{id}/process', [AdminController::class, 'processOrder']);
});
Route::put('/admin/settings/qris', [App\Http\Controllers\Admin\SettingController::class, 'updateQris'])->name('admin.settings.update-qris');
Route::put('/admin/settings/qris', [App\Http\Controllers\Admin\SettingController::class, 'updateQris'])->name('admin.settings.update-qris');
