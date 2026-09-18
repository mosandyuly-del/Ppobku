<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

if (!Schema::hasTable('settings')) {
    try {
        Schema::create('settings', function ($table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    } catch (\Exception $e) {}
}

if (!Schema::hasTable('transactions')) {
    try {
        Schema::create('transactions', function ($table) {
            $table->id();
            $table->string('trx_id')->unique();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('target_no')->nullable();
            $table->integer('price')->default(0);
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });
    } catch (\Exception $e) {}
}

if (!Schema::hasTable('blacklists')) {
    try {
        Schema::create('blacklists', function ($table) {
            $table->id();
            $table->string('target_no')->unique();
            $table->string('reason')->nullable();
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

// Process Checkout & Buat Multichannel Payment Midtrans
Route::post('/checkout', function (Request $request) {
    $productCode = $request->input('product_code');
    $targetNo = trim($request->input('target_no'));

    if (Schema::hasTable('blacklists')) {
        $isBlacklisted = DB::table('blacklists')->where('target_no', $targetNo)->exists();
        if ($isBlacklisted) {
            return back()->with('error', 'Nomor tujuan diblokir karena aktivitas mencurigakan.');
        }
    }

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

    // Midtrans Configuration - Mengaktifkan Semua Channel Pembayaran
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
            // Mengaktifkan QRIS, Virtual Account, E-Wallet, & Mini Market
            'enabled_payments' => [
                'gopay', 'qris', 'shopeepay', 
                'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va', 'other_va',
                'indomaret', 'alfamart'
            ]
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

// Admin Dashboard
Route::get('/admin', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $searchTrx = $request->input('search_trx');
    $recentTrx = collect();

    if (Schema::hasTable('transactions')) {
        $trxQuery = DB::table('transactions')->orderBy('id', 'desc');

        if ($searchTrx) {
            $trxQuery->where('trx_id', 'LIKE', '%' . $searchTrx . '%')
                     ->orWhere('target_no', 'LIKE', '%' . $searchTrx . '%');
        }

        $recentTrx = $trxQuery->limit(50)->get();
    }

    $products = collect();
    $totalProducts = 0;
    $activeProductsCount = 0;

    if (Schema::hasTable('products')) {
        $products = DB::table('products')->limit(150)->get();
        $totalProducts = DB::table('products')->count();
        $activeProductsCount = DB::table('products')->where('status', 'active')->count();
    }

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
        'announcementText' => get_setting('ANNOUNCEMENT_TEXT'),
        'rekap' => $rekap,
        'recentTrx' => $recentTrx
    ]);
})->middleware('auth');

Route::post('/admin/retry-digiflazz', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    $trxId = $request->input('trx_id');
    if (Schema::hasTable('transactions')) {
        $trx = DB::table('transactions')->where('trx_id', $trxId)->first();

        if ($trx) {
            $username = get_setting('DIGIFLAZZ_USERNAME');
            $apiKey = get_setting('DIGIFLAZZ_KEY');

            if ($username && $apiKey) {
                $sign = md5($username . $apiKey . $trxId);
                $payload = [
                    'username' => $username,
                    'buyer_sku_code' => $trx->product_code ?? '',
                    'customer_no' => $trx->target_no ?? '',
                    'ref_id' => $trxId,
                    'sign' => $sign
                ];

                $ch = curl_init('https://api.digiflazz.com/v1/transaction');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_exec($ch);
                curl_close($ch);

                DB::table('transactions')->where('trx_id', $trxId)->update(['status' => 'SUCCESS', 'updated_at' => now()]);
                return back()->with('success', 'Berhasil melakukan tembak ulang ke Digiflazz!');
            }
        }
    }
    return back()->with('error', 'Gagal memproses tembak ulang.');
})->middleware('auth');

Route::post('/admin/update-status-manual', function (Request $request) {
    if (!Auth::check()) return redirect('/login');
    $trxId = $request->input('trx_id');
    $status = $request->input('status');

    if (Schema::hasTable('transactions')) {
        DB::table('transactions')->where('trx_id', $trxId)->update(['status' => $status, 'updated_at' => now()]);
    }
    return back()->with('success', 'Status transaksi berhasil diubah secara manual!');
})->middleware('auth');

Route::get('/admin/export-csv', function () {
    if (!Auth::check()) return redirect('/login');

    $fileName = 'rekap_penjualan_' . date('Y-m-d') . '.csv';
    $transactions = Schema::hasTable('transactions') ? DB::table('transactions')->orderBy('id', 'desc')->get() : collect();

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use($transactions) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['ID Transaksi', 'Nama Produk', 'Kode Produk', 'Nomor Tujuan', 'Harga Jual', 'Status', 'Tanggal']);

        foreach ($transactions as $row) {
            fputcsv($file, [$row->trx_id, $row->product_name, $row->product_code ?? '', $row->target_no, $row->price, $row->status, $row->created_at]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->middleware('auth');

Route::post('/admin/add-blacklist', function (Request $request) {
    if (!Auth::check()) return redirect('/login');
    $targetNo = trim($request->input('target_no'));
    $reason = $request->input('reason');

    if ($targetNo && Schema::hasTable('blacklists')) {
        DB::table('blacklists')->updateOrInsert(['target_no' => $targetNo], ['reason' => $reason, 'created_at' => now()]);
    }
    return back()->with('success', 'Nomor berhasil ditambahkan ke daftar terblokir (Blacklist)!');
})->middleware('auth');

Route::post('/admin/save-settings', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    set_setting('DIGIFLAZZ_USERNAME', $request->input('DIGIFLAZZ_USERNAME'));
    set_setting('DIGIFLAZZ_KEY', $request->input('DIGIFLAZZ_KEY'));
    set_setting('MARKUP_FLAT', $request->input('MARKUP_FLAT'));
    set_setting('MIDTRANS_CLIENT_KEY', $request->input('MIDTRANS_CLIENT_KEY'));
    set_setting('MIDTRANS_SERVER_KEY', $request->input('MIDTRANS_SERVER_KEY'));
    set_setting('ANNOUNCEMENT_TEXT', $request->input('ANNOUNCEMENT_TEXT'));

    return back()->with('success', 'Semua Pengaturan Admin & Store berhasil diperbarui!');
})->middleware('auth');

Route::post('/admin/sync-now', function () {
    if (!Auth::check()) return redirect('/login');
    try {
        \Illuminate\Support\Facades\Artisan::call('digiflazz:sync');
    } catch (\Exception $e) {}
    return back()->with('success', 'Berhasil melakukan sinkronisasi data produk!');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
