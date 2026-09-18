<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Full Features - MOSANDY STORE</title>
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
                <span class="font-extrabold text-base tracking-tight">MOSANDY STORE <span class="text-blue-400">ADMIN CONTROL</span></span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="/admin/export-csv" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg font-bold shadow transition">📥 Unduh Laporan CSV</a>
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

        @if(session('error'))
            <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- 1. Ringkasan Finansial & Saldo -->
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
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Katalog Tersedia</span>
            </div>
        </div>

        <!-- 2. Form Pengaturan API & Pengumuman Front Web -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Pengaturan Integrasi & Pengumuman Store</h3>
                    <p class="text-xs text-slate-500">Kelola API Key Digiflazz, Midtrans, & Running Text Pengumuman Web Front.</p>
                </div>
                <form action="/admin/sync-now" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-md">
                        ⚡ Sync Produk Digiflazz
                    </button>
                </form>
            </div>

            <form action="/admin/save-settings" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Digiflazz Username</label>
                        <input type="text" name="DIGIFLAZZ_USERNAME" value="{{ $digiflazzUsername ?? '' }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Digiflazz Production Key</label>
                        <input type="text" name="DIGIFLAZZ_KEY" value="{{ $digiflazzKey ?? '' }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Markup Keuntungan Flat (Rp)</label>
                        <input type="number" name="MARKUP_FLAT" value="{{ $markupFlat ?? 1500 }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Midtrans Client Key</label>
                        <input type="text" name="MIDTRANS_CLIENT_KEY" value="{{ $midtransClientKey ?? '' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Midtrans Server Key</label>
                        <input type="text" name="MIDTRANS_SERVER_KEY" value="{{ $midtransServerKey ?? '' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Teks Pengumuman Bar Web</label>
                        <input type="text" name="ANNOUNCEMENT_TEXT" value="{{ $announcementText ?? 'Sistem Otomatis 24 Jam Nonstop • QRIS All Payment • Proses Instan 1-3 Detik' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-md transition">
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. Manajemen Transaksi Real-time & Manual Action -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Kelola Transaksi & Manual Action</h3>
                    <p class="text-xs text-slate-500">Lakukan Tembak Ulang Digiflazz atau ubah status transaksi secara manual.</p>
                </div>
                <form action="/admin" method="GET" class="flex items-center space-x-2">
                    <input type="text" name="search_trx" value="{{ request('search_trx') }}" placeholder="Cari Trx ID / No HP..." class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none">
                    <button type="submit" class="bg-slate-800 text-white px-3 py-2 rounded-xl text-xs font-bold">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 font-extrabold uppercase text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="p-3">ID Transaksi</th>
                            <th class="p-3">Produk</th>
                            <th class="p-3">No Tujuan</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi Manual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        <?php if(isset($recentTrx) && count($recentTrx) > 0): ?>
                            <?php foreach($recentTrx as $t): ?>
                                <tr>
                                    <td class="p-3 font-mono font-bold text-slate-900"><?php echo $t->trx_id; ?></td>
                                    <td class="p-3"><?php echo $t->product_name; ?></td>
                                    <td class="p-3 font-mono"><?php echo $t->target_no; ?></td>
                                    <td class="p-3 font-bold text-blue-600">Rp <?php echo number_format($t->price, 0, ',', '.'); ?></td>
                                    <td class="p-3">
                                        <?php if($t->status == 'SUCCESS'): ?>
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">SUCCESS</span>
                                        <?php elseif($t->status == 'PENDING'): ?>
                                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px]">PENDING</span>
                                        <?php else: ?>
                                            <span class="bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded text-[10px]">FAILED</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-right space-x-1">
                                        <form action="/admin/retry-digiflazz" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="trx_id" value="<?php echo $t->trx_id; ?>">
                                            <button type="submit" class="bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg hover:bg-blue-700">⚡ Tembak Digiflazz</button>
                                        </form>
                                        <form action="/admin/update-status-manual" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="trx_id" value="<?php echo $t->trx_id; ?>">
                                            <input type="hidden" name="status" value="SUCCESS">
                                            <button type="submit" class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded-lg hover:bg-emerald-700">✔ Sukseskan</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="p-4 text-center text-slate-400 font-bold">Belum ada transaksi recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Keamanan & Blacklist Nomor HP -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-base font-extrabold text-slate-900">Blacklist & Anti-Spam Nomor HP</h3>
            <form action="/admin/add-blacklist" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="target_no" required placeholder="Contoh: 081234567890" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none w-64">
                <input type="text" name="reason" placeholder="Alasan (opsional)" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none flex-1">
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow">🚫 Blokir Nomor</button>
            </form>
        </div>

    </main>
</body>
</html>
