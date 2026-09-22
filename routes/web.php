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

function seed_default_products() {
    if (!Schema::hasTable('products')) {
        Schema::create('products', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->string('sku')->nullable();
            $table->integer('price')->default(0);
            $table->integer('original_price')->default(0);
            $table->string('status')->default('active');
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    if (DB::table('products')->count() == 0) {
        $products = [
            ['code' => 'S1', 'name' => 'Telkomsel Pulsa 1.000', 'price' => 2800, 'brand' => 'telkomsel', 'category' => 'pulsa'],
            ['code' => 'S5', 'name' => 'Telkomsel Pulsa 5.000', 'price' => 6700, 'brand' => 'telkomsel', 'category' => 'pulsa'],
            ['code' => 'S10', 'name' => 'Telkomsel Pulsa 10.000', 'price' => 11700, 'brand' => 'telkomsel', 'category' => 'pulsa'],
            ['code' => 'I5', 'name' => 'Indosat Pulsa 5.000', 'price' => 6600, 'brand' => 'indosat', 'category' => 'pulsa'],
            ['code' => 'I10', 'name' => 'Indosat Pulsa 10.000', 'price' => 11600, 'brand' => 'indosat', 'category' => 'pulsa'],
            ['code' => 'X5', 'name' => 'XL Pulsa 5.000', 'price' => 6700, 'brand' => 'xl', 'category' => 'pulsa'],
            ['code' => 'X10', 'name' => 'XL Pulsa 10.000', 'price' => 11700, 'brand' => 'xl', 'category' => 'pulsa'],
            ['code' => 'AX5', 'name' => 'Axis Pulsa 5.000', 'price' => 6650, 'brand' => 'axis', 'category' => 'pulsa'],
            ['code' => 'AX10', 'name' => 'Axis Pulsa 10.000', 'price' => 11650, 'brand' => 'axis', 'category' => 'pulsa'],
            ['code' => 'T5', 'name' => 'Tri Pulsa 5.000', 'price' => 6200, 'brand' => 'tri', 'category' => 'pulsa'],
            ['code' => 'T10', 'name' => 'Tri Pulsa 10.000', 'price' => 11200, 'brand' => 'tri', 'category' => 'pulsa'],
            ['code' => 'SD1', 'name' => 'Telkomsel Data OMG 1 GB 3 Hari', 'price' => 14500, 'brand' => 'telkomsel', 'category' => 'data'],
            ['code' => 'ID1', 'name' => 'Indosat Freedom Internet 1 GB 5 Hari', 'price' => 10500, 'brand' => 'indosat', 'category' => 'data'],
            ['code' => 'XD1', 'name' => 'XL Data Xtra Combo Flex 1.5 GB 30 Hari', 'price' => 18500, 'brand' => 'xl', 'category' => 'data'],
            ['code' => 'AXD2', 'name' => 'Axis Data Bronet 2.5 GB 5 Hari', 'price' => 15160, 'brand' => 'axis', 'category' => 'data'],
            ['code' => 'TD1', 'name' => 'Tri Data Happy 1 GB 5 Hari', 'price' => 9500, 'brand' => 'tri', 'category' => 'data'],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['code' => $p['code']],
                [
                    'name' => $p['name'],
                    'sku' => $p['code'],
                    'price' => $p['price'],
                    'original_price' => $p['price'],
                    'status' => 'active',
                    'category' => $p['category'],
                    'brand' => $p['brand'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
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

    if (!Schema::hasTable('products') || DB::table('products')->count() == 0) {
        try {
            seed_default_products();
        } catch (\Exception $e) {}
    }

    if (Schema::hasTable('products')) {
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

// Admin Dashboard Clean
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
        $products = DB::table('products')->orderBy('id', 'desc')->get();
        $totalProducts = DB::table('products')->count();
        $activeProductsCount = DB::table('products')->where('status', 'active')->count();
    }

    return view('admin.dashboard', [
        'products' => $products,
        'totalProducts' => $totalProducts,
        'activeProductsCount' => $activeProductsCount,
        'recentTrx' => $recentTrx
    ]);
})->middleware('auth');

// Ubah Status Transaksi Manual
Route::post('/admin/update-trx-status', function (Request $request) {
    if (!Auth::check()) return redirect('/login');

    $trxId = $request->input('trx_id');
    $status = $request->input('status');

    if (Schema::hasTable('transactions')) {
        DB::table('transactions')->where('trx_id', $trxId)->update([
            'status' => $status,
            'updated_at' => now()
        ]);
        return back()->with('success', "Status transaksi {$trxId} berhasil diubah ke {$status}.");
    }
    return back()->with('error', 'Gagal memperbarui transaksi.');
})->middleware('auth');

// Tambah Produk Manual
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
        return back()->with('success', 'Produk "' . $name . '" berhasil ditambahkan/diperbarui!');
    }

    return back()->with('error', 'Gagal menambahkan produk. Pastikan Kode, Nama, dan Harga terisi dengan benar.');
})->middleware('auth');

// Hapus Produk
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
