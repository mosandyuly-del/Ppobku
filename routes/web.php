<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

if (Schema::hasTable('products') && !Schema::hasTable('settings')) {
    try {
        Schema::create('settings', function ($table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    } catch (\Exception $e) {}
}

function get_setting($key, $default = '') {
    if (Schema::hasTable('settings')) {
        $item = DB::table('settings')->where('key', $key)->first();
        if ($item && !empty($item->value)) {
            return $item->value;
        }
    }
    return env($key, $default);
}

function set_setting($key, $value) {
    if (Schema::hasTable('settings')) {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
    }
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cek-pesanan', function (Request $request) {
    $search = $request->input('q');
    $transaction = null;

    if ($search && Schema::hasTable('transactions')) {
        $transaction = DB::table('transactions')
            ->where('trx_id', $search)
            ->orWhere('target_no', $search)
            ->orderBy('id', 'desc')
            ->first();
    }

    return view('track', [
        'search' => $search,
        'transaction' => $transaction
    ]);
});

Route::get('/category/{slug}', function ($slug) {
    $products = collect();

    if (Schema::hasTable('products')) {
        if (DB::table('products')->count() == 0) {
            try {
                \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
            } catch (\Exception $e) {}
        }

        $categoryMap = [
            'game' => ['game', 'voucher', 'mobile legends', 'free fire', 'pubg'],
            'pulsa' => ['pulsa', 'telkomsel', 'indosat', 'xl', 'axis', 'tri', 'smartfren'],
            'data' => ['data', 'paket', 'internet'],
            'pln-token' => ['pln', 'token'],
            'pln-bill' => ['pln', 'tagihan'],
            'pdam' => ['pdam', 'air'],
            'e-money' => ['e-money', 'wallet', 'dana', 'gopay', 'ovo', 'shopeepay'],
        ];

        $keywords = $categoryMap[$slug] ?? [$slug];

        $query = DB::table('products')->where('status', 'active');
        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('category', 'LIKE', '%' . $word . '%')
                  ->orWhere('name', 'LIKE', '%' . $word . '%')
                  ->orWhere('brand', 'LIKE', '%' . $word . '%');
            }
        });

        $products = $query->get();

        if ($products->isEmpty()) {
            $products = DB::table('products')->where('status', 'active')->get();
        }
    }

    return view('category', [
        'title' => strtoupper(str_replace('-', ' ', $slug)),
        'products' => $products
    ]);
});

// Process Checkout & Buat QRIS Midtrans
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
    $totalBayar = (int) ($product->price ?? 0);

    if (Schema::hasTable('transactions')) {
        DB::table('transactions')->insert([
            'trx_id' => $trxId,
            'product_name' => $product->name ?? 'Produk PPOB',
            'product_code' => $productCode,
            'target_no' => $targetNo,
            'price' => $totalBayar,
            'status' => 'PENDING',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    // Konfigurasi Midtrans Snap
    $serverKey = get_setting('MIDTRANS_SERVER_KEY');
    $snapToken = null;

    if ($serverKey) {
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = (get_setting('MIDTRANS_MODE') === 'production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $trxId,
                'gross_amount' => $totalBayar,
            ],
            'item_details' => [[
                'id' => $productCode,
                'price' => $totalBayar,
                'quantity' => 1,
                'name' => substr($product->name ?? 'Produk PPOB', 0, 50)
            ]],
            'customer_details' => [
                'first_name' => 'Pelanggan',
                'phone' => $targetNo,
            ],
            'enabled_payments' => ['gopay', 'qris', 'shopeepay']
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {}
    }

    return view('checkout', [
        'trx_id' => $trxId,
        'product' => $product,
        'target_no' => $targetNo,
        'total' => $totalBayar,
        'snap_token' => $snapToken,
        'client_key' => get_setting('MIDTRANS_CLIENT_KEY')
    ]);
});

// Webhook Callback dari Midtrans
Route::post('/api/midtrans-callback', function (Request $request) {
    $serverKey = get_setting('MIDTRANS_SERVER_KEY');
    
    if (!$serverKey) {
        return response()->json(['message' => 'Server Key not configured'], 400);
    }

    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = (get_setting('MIDTRANS_MODE') === 'production');

    try {
        $notif = new \Midtrans\Notification();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Invalid Notification Payload'], 400);
    }

    $transactionStatus = $notif->transaction_status;
    $orderId = $notif->order_id;
    $fraudStatus = $notif->fraud_status;

    $isPaid = false;
    if ($transactionStatus == 'capture') {
        if ($fraudStatus == 'accept') $isPaid = true;
    } else if ($transactionStatus == 'settlement') {
        $isPaid = true;
    }

    if ($isPaid) {
        $trx = DB::table('transactions')->where('trx_id', $orderId)->first();

        if ($trx && $trx->status !== 'SUCCESS') {
            DB::table('transactions')->where('trx_id', $orderId)->update([
                'status' => 'SUCCESS',
                'updated_at' => now()
            ]);

            // Eksekusi API Digiflazz Otomatis
            $username = get_setting('DIGIFLAZZ_USERNAME');
            $apiKey = get_setting('DIGIFLAZZ_KEY');

            if ($username && $apiKey) {
                $sign = md5($username . $apiKey . $orderId);
                $payload = [
                    'username' => $username,
                    'buyer_sku_code' => $trx->product_code ?? '',
                    'customer_no' => $trx->target_no ?? '',
                    'ref_id' => $orderId,
                    'sign' => $sign
                ];

                $ch = curl_init('https://api.digiflazz.com/v1/transaction');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_exec($ch);
                curl_close($ch);
            }
        }
    } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
        DB::table('transactions')->where('trx_id', $orderId)->update([
            'status' => 'FAILED',
            'updated_at' => now()
        ]);
    }

    return response()->json(['status' => 'OK']);
});

// Admin Auth & Dashboard Routes
Route::get('/login', function () {
    return view('auth.login');
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

Route::get('/admin', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $products = DB::table('products')->limit(150)->get();
    $totalProducts = DB::table('products')->count();
    $activeProductsCount = DB::table('products')->where('status', 'active')->count();

    $rekap = [
        'total_omset' => 0,
        'total_profit' => 0,
        'trx_success' => 0,
        'trx_pending' => 0,
        'trx_failed' => 0,
    ];

    if (Schema::hasTable('transactions')) {
        $successTrx = DB::table('transactions')->where('status', 'SUCCESS')->get();
        $rekap['trx_success'] = $successTrx->count();
        $rekap['trx_pending'] = DB::table('transactions')->where('status', 'PENDING')->count();
        $rekap['trx_failed'] = DB::table('transactions')->whereIn('status', ['FAILED', 'EXPIRED'])->count();

        $markupFlat = (int) get_setting('MARKUP_FLAT', 1500);
        foreach ($successTrx as $trx) {
            $rekap['total_omset'] += $trx->price;
            $rekap['total_profit'] += $markupFlat;
        }
    }

    $username = get_setting('DIGIFLAZZ_USERNAME');
    $apiKey = get_setting('DIGIFLAZZ_KEY');
    $digiflazzBalance = 0;

    if ($username && $apiKey) {
        $sign = md5($username . $apiKey . 'depo');
        $payload = ['cmd' => 'deposit', 'username' => $username, 'sign' => $sign];

        $ch = curl_init('https://api.digiflazz.com/v1/cek-saldo');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);

        $resData = json_decode($response, true);
        if (isset($resData['data']['deposit'])) {
            $digiflazzBalance = $resData['data']['deposit'];
        }
    }

    return view('admin.dashboard', [
        'products' => $products,
        'totalProducts' => $totalProducts,
        'activeProductsCount' => $activeProductsCount,
        'digiflazzBalance' => $digiflazzBalance,
        'digiflazzUsername' => $username,
        'digiflazzKey' => $apiKey,
        'markupFlat' => get_setting('MARKUP_FLAT', 1500),
        'midtransClientKey' => get_setting('MIDTRANS_CLIENT_KEY'),
        'midtransServerKey' => get_setting('MIDTRANS_SERVER_KEY'),
        'midtransMode' => get_setting('MIDTRANS_MODE', 'production'),
        'rekap' => $rekap
    ]);
})->middleware('auth');

Route::post('/admin/save-settings', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    set_setting('DIGIFLAZZ_USERNAME', $request->input('DIGIFLAZZ_USERNAME'));
    set_setting('DIGIFLAZZ_KEY', $request->input('DIGIFLAZZ_KEY'));
    set_setting('MARKUP_FLAT', $request->input('MARKUP_FLAT'));
    set_setting('MIDTRANS_CLIENT_KEY', $request->input('MIDTRANS_CLIENT_KEY'));
    set_setting('MIDTRANS_SERVER_KEY', $request->input('MIDTRANS_SERVER_KEY'));
    set_setting('MIDTRANS_MODE', $request->input('MIDTRANS_MODE'));

    try {
        \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
    } catch (\Exception $e) {}

    return back()->with('success', 'Pengaturan Midtrans & Digiflazz berhasil diperbarui!');
})->middleware('auth');

Route::post('/admin/sync-now', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
    } catch (\Exception $e) {}

    return back()->with('success', 'Berhasil melakukan sinkronisasi data produk!');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
