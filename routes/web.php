<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CallbackController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{invoice_number}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order/{invoice_number}/upload', [OrderController::class, 'uploadProof'])->name('order.upload');
Route::get('/cek-status', [OrderController::class, 'checkStatusForm'])->name('order.check');
Route::post('/cek-status', [OrderController::class, 'checkStatusSearch'])->name('order.check.search');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/orders', [AdminController::class, 'index'])->name('admin.orders');
    Route::post('/admin/orders/{invoice_number}/update', [AdminController::class, 'updateStatus'])->name('admin.orders.update');

    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::post('/admin/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::post('/admin/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.delete');
});

Route::post('/api/callback/digiflazz', [CallbackController::class, 'handleDigiflazz'])->name('callback.digiflazz');
