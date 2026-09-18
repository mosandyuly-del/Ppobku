<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    
    <!-- Navbar Admin -->
    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3.5 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-black text-white">M</div>
                <span class="font-extrabold text-base tracking-tight">MOSANDY STORE <span class="text-blue-400">ADMIN</span></span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="/" target="_blank" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 px-3 py-1.5 rounded-lg font-bold">Lihat Web &rarr;</a>
                <a href="/logout" class="text-xs bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg font-bold">Keluar</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- 1. Card Ringkasan Utama -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Saldo Digiflazz Real</span>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($digiflazzBalance ?? 0, 0, ',', '.') }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Production Balance</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Omset Penjualan</span>
                <h3 class="text-2xl font-black text-blue-600 mt-1">Rp {{ number_format($rekap['total_omset'] ?? 0, 0, ',', '.') }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Total Transaksi Berhasil</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Estimasi Keuntungan</span>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">Rp {{ number_format($rekap['total_profit'] ?? 0, 0, ',', '.') }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Margin Profit Bersih</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Produk Aktif</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($activeProductsCount ?? 0) }} / {{ number_format($totalProducts ?? 0) }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Siap Dijual</span>
            </div>
        </div>

        <!-- 2. Form Pengaturan API Key & Markup -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Pengaturan API Key Digiflazz Production</h3>
                    <p class="text-xs text-slate-500">Ubah kredensial API Key dan markup keuntungan tanpa restart server.</p>
                </div>
                <form action="/admin/sync-now" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-md">
                        ⚡ Sync Produk Sekarang
                    </button>
                </form>
            </div>

            <form action="/admin/save-settings" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Digiflazz Username</label>
                        <input type="text" name="DIGIFLAZZ_USERNAME" value="{{ $digiflazzUsername ?? '' }}" placeholder="Username Digiflazz" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Production API Key</label>
                        <input type="text" name="DIGIFLAZZ_KEY" value="{{ $digiflazzKey ?? '' }}" placeholder="Production API Key" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Markup Keuntungan Flat (Rp)</label>
                        <input type="number" name="MARKUP_FLAT" value="{{ $markupFlat ?? 1500 }}" placeholder="Contoh: 1500" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-md transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. Rekapitulasi Penjualan & Performa Transaksi -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-base font-extrabold text-slate-900">Rekapitulasi Penjualan & Transaksi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-700 uppercase">Transaksi Sukses</span>
                    <h4 class="text-xl font-black text-emerald-800 mt-1">{{ number_format($rekap['trx_success'] ?? 0) }} Pesanan</h4>
                </div>
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <span class="text-xs font-bold text-amber-700 uppercase">Pending / Menunggu Pembayaran</span>
                    <h4 class="text-xl font-black text-amber-800 mt-1">{{ number_format($rekap['trx_pending'] ?? 0) }} Pesanan</h4>
                </div>
                <div class="p-4 bg-rose-50 rounded-xl border border-rose-100">
                    <span class="text-xs font-bold text-rose-700 uppercase">Transaksi Gagal</span>
                    <h4 class="text-xl font-black text-rose-800 mt-1">{{ number_format($rekap['trx_failed'] ?? 0) }} Pesanan</h4>
                </div>
            </div>
        </div>

        <!-- 4. Tabel Perbandingan Harga Modal vs Harga Jual & Margin per Produk -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 overflow-hidden">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Analisis Perbandingan Harga & Margin Produk</h3>
                    <p class="text-xs text-slate-500">Daftar harga modal Digiflazz vs Harga Jual toko kamu.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700 border-collapse">
                    <thead class="bg-slate-50 text-slate-900 font-extrabold uppercase border-b border-slate-200">
                        <tr>
                            <th class="p-3">Kode SKU</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Harga Modal</th>
                            <th class="p-3">Harga Jual</th>
                            <th class="p-3">Profit (Rp)</th>
                            <th class="p-3">Margin %</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <?php if(isset($products) && count($products) > 0): ?>
                            <?php foreach($products as $p): ?>
                                <?php
                                    $modal = $p->price_original ?? 0;
                                    $jual = $p->price ?? 0;
                                    $untung = $jual - $modal;
                                    $marginPct = $modal > 0 ? ($untung / $modal) * 100 : 0;
                                ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-mono font-bold text-slate-500"><?php echo $p->code ?? $p->sku; ?></td>
                                    <td class="p-3 font-bold text-slate-900"><?php echo $p->name; ?></td>
                                    <td class="p-3"><span class="bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded uppercase text-[10px]"><?php echo $p->category ?? 'Umum'; ?></span></td>
                                    <td class="p-3 font-semibold text-slate-600">Rp <?php echo number_format($modal, 0, ',', '.'); ?></td>
                                    <td class="p-3 font-bold text-blue-600">Rp <?php echo number_format($jual, 0, ',', '.'); ?></td>
                                    <td class="p-3 font-black text-emerald-600">+Rp <?php echo number_format($untung, 0, ',', '.'); ?></td>
                                    <td class="p-3 font-black text-indigo-600">+<?php echo number_format($marginPct, 1); ?>%</td>
                                    <td class="p-3">
                                        <?php if(($p->status ?? 'active') == 'active'): ?>
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">AKTIF</span>
                                        <?php else: ?>
                                            <span class="bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded text-[10px]">OFF</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-400 font-bold">Belum ada produk terdaftar. Klik "Sync Produk Sekarang" untuk mengunduh produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>
