<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Halaman Utama
Route::get('/', function () {
    if (view()->exists('home')) {
        return view('home');
    }
    return view('welcome');
});

// Route Kategori Produk Dinamis
Route::get('/category/{slug}', function ($slug) {
    $products = collect();

    if (Schema::hasTable('products')) {
        if (DB::table('products')->count() == 0) {
            \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
        }

        $categoryMap = [
            'game' => ['game', 'voucher', 'mobile legends', 'free fire', 'pubg'],
            'pulsa' => ['pulsa', 'telkomsel', 'indosat', 'xl', 'axis', 'tri', 'smartfren'],
            'data' => ['data', 'paket', 'internet'],
            'pln-token' => ['pln', 'token'],
            'pln-bill' => ['pln', 'tagihan'],
            'pdam' => ['pdam', 'air'],
        ];

        $keywords = $categoryMap[$slug] ?? [$slug];

        $query = DB::table('products');
        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('category', 'LIKE', '%' . $word . '%')
                  ->orWhere('name', 'LIKE', '%' . $word . '%')
                  ->orWhere('brand', 'LIKE', '%' . $word . '%');
            }
        });

        $products = $query->get();

        if ($products->isEmpty()) {
            $products = DB::table('products')->get();
        }
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

// Process Checkout & QRIS Generator
Route::post('/checkout', function (Request $request) {
    $productCode = $request->input('product_code');
    $targetNo = $request->input('target_no');

    $product = DB::table('products')
        ->where('code', $productCode)
        ->orWhere('sku', $productCode)
        ->first();

    if (!$product) {
        return back()->with('error', 'Produk tidak ditemukan!');
    }

    $trxId = 'TRX-' . time() . rand(100, 999);
    $totalBayar = $product->price ?? 0;

    $qrisPayload = "00020101021126570011ID.NOBU.WWW011893600503000008807902150000000000000000303UMI51440014ID.QRIS.WWW0215ID10200212345675204581253033605802ID5913MOSANDY STORE6007JAKARTA63046C41";

    return view('checkout', [
        'trx_id' => $trxId,
        'product' => $product,
        'target_no' => $targetNo,
        'total' => $totalBayar,
        'qris_payload' => $qrisPayload
    ]);
});

// Route Login
Route::get('/login', function () {
    if (view()->exists('auth.login')) {
        return view('auth.login');
    }
    return view('welcome');
})->name('login');

$loginHandler = function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }

    return back()->withErrors([
        'email' => 'Kredensial email atau password admin salah.',
    ]);
};

Route::post('/login', $loginHandler);
Route::post('/login/perform', $loginHandler)->name('login.perform');

// Route Admin Dashboard
Route::get('/admin', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $products = DB::table('products')->limit(100)->get();
    $totalProducts = DB::table('products')->count();

    $totalMarginPercent = 0;
    $validCount = 0;
    foreach ($products as $p) {
        $modal = $p->price_original ?? 0;
        $jual = $p->price ?? 0;
        if ($modal > 0) {
            $totalMarginPercent += (($jual - $modal) / $modal) * 100;
            $validCount++;
        }
    }
    $avgMarginPercent = $validCount > 0 ? ($totalMarginPercent / $validCount) : 0;

    return view('admin.dashboard', [
        'products' => $products,
        'totalProducts' => $totalProducts,
        'totalTransactions' => 0,
        'avgMarginPercent' => $avgMarginPercent
    ]);
})->middleware('auth');

Route::post('/admin/sync-now', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');

    return back()->with('success', 'Berhasil melakukan sinkronisasi ulang data produk Digiflazz!');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
