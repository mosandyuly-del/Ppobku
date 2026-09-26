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
            $table->text('sn')->nullable();
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
        $categoryMap = [
            'game' => ['game', 'voucher', 'mobile legends', 'free fire', 'pubg'],
            'pulsa' => ['pulsa', 'telkomsel', 'indosat', 'xl', 'axis', 'tri', 'smartfren'],
            'data' => ['data', 'paket', 'internet'],
            'pln-token' => ['pln', 'token'],
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

Route::post('/checkout', function (Request $request) {
    $productCode = $request->input('product_code');
    $targetNo = trim($request->input('target_no'));

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

    return view('checkout', [
        'trx_id' => $trxId,
        'product' => $product,
        'target_no' => $targetNo,
        'total' => $totalBayar
    ]);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }
    return back()->withErrors(['email' => 'Email atau password salah.']);
});

// Admin Dashboard
Route::get('/admin', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    $recentTrx = collect();
    if (Schema::hasTable('transactions')) {
        $recentTrx = DB::table('transactions')->orderBy('id', 'desc')->limit(50)->get();
    }

    $products = collect();
    $totalProducts = 0;
    $activeProductsCount = 0;

    if (Schema::hasTable('products')) {
        $products = DB::table('products')->orderBy('id', 'desc')->get();
        $totalProducts = DB::table('products')->count();
        $activeProductsCount = DB::table('products')->where('status', 'active')->count();
    }

    return view('admin.dashboard', [
        'products' => $products,
        'totalProducts' => $totalProducts,
        'activeProductsCount' => $activeProductsCount,
        'recentTrx' => $recentTrx,
        'digiflazzUsername' => get_setting('DIGIFLAZZ_USERNAME'),
        'digiflazzKey' => get_setting('DIGIFLAZZ_KEY')
    ]);
})->middleware('auth');

// PROSES EKSEKUSI API DIGIFLAZZ METHOD POST JSON DENGAN DETEKSI RC 45
Route::post('/admin/update-trx-status', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    $trxId = $request->input('trx_id');
    $status = $request->input('status');

    if ($status === 'SUCCESS') {
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
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                
                $response = curl_exec($ch);
                curl_close($ch);

                $resData = json_decode($response, true);

                if (isset($resData['data'])) {
                    $sn = $resData['data']['sn'] ?? '';
                    $digiStatus = $resData['data']['status'] ?? 'PENDING';
                    $rc = $resData['data']['rc'] ?? '';

                    if ($digiStatus === 'Sukses' || $digiStatus === 'SUCCESS') {
                        DB::table('transactions')->where('trx_id', $trxId)->update([
                            'status' => 'SUCCESS',
                            'sn' => $sn,
                            'updated_at' => now()
                        ]);
                        return back()->with('success', "API Digiflazz Sukses Terkirim! SN: {$sn}");
                    } else if ($rc === '45') {
                        return back()->with('error', "Gagal (RC 45): IP Server Railway diblokir oleh Digiflazz. Mohon KOSONGKAN/NON-AKTIFKAN IP Whitelist di menu Atur Koneksi pada Akun Member Digiflazz kamu.");
                    } else if ($digiStatus === 'Gagal' || $digiStatus === 'FAILED') {
                        DB::table('transactions')->where('trx_id', $trxId)->update([
                            'status' => 'FAILED',
                            'updated_at' => now()
                        ]);
                        return back()->with('error', "Digiflazz Menolak Transaksi. Kode Respon (RC): {$rc}");
                    } else {
                        DB::table('transactions')->where('trx_id', $trxId)->update([
                            'status' => 'PENDING',
                            'updated_at' => now()
                        ]);
                        return back()->with('success', "Transaksi dikirim ke Digiflazz & sedang diproses operator (Pending).");
                    }
                }
            } else {
                return back()->with('error', 'Username atau Key Digiflazz belum dikonfigurasi di admin.');
            }
        }
    }

    DB::table('transactions')->where('trx_id', $trxId)->update([
        'status' => $status,
        'updated_at' => now()
    ]);

    return back()->with('success', "Status transaksi {$trxId} diperbarui.");
})->middleware('auth');

Route::post('/admin/add-product', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    $code = strtoupper(trim($request->input('code')));
    $name = trim($request->input('name'));
    $price = (int) $request->input('price');
    $category = strtolower(trim($request->input('category')));
    $brand = strtolower(trim($request->input('brand')));
    $description = $request->input('description');

    if ($code && $name && $price > 0 && Schema::hasTable('products')) {
        DB::table('products')->updateOrInsert(
            ['code' => $code],
            [
                'name' => $name,
                'sku' => $code,
                'price' => $price,
                'original_price' => $price,
                'status' => 'active',
                'category' => $category,
                'brand' => $brand,
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        return back()->with('success', 'Produk berhasil disimpan!');
    }
    return back()->with('error', 'Gagal menyimpan produk.');
})->middleware('auth');

Route::post('/admin/delete-product', function (Request $request) {
    if (!Auth::check()) return redirect('/login');
    $code = $request->input('code');

    if ($code && Schema::hasTable('products')) {
        DB::table('products')->where('code', $code)->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }
    return back()->with('error', 'Gagal menghapus produk.');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/cek-ip', function() {
    $ip = @file_get_contents('https://api.ipify.org');
    return "IP Railway Kamu Saat Ini: " . ($ip ?: 'Gagal mengambil IP');
});

Route::get('/flyer', function () {
    return view('flyer');
});
