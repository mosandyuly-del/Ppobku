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
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
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

    <main class="max-w-6xl mx-auto px-4 py-6 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Informatif Statistik & Saldo -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Saldo Digiflazz Deposit</span>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($digiflazzBalance ?? 0, 0, ',', '.') }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Production Real Balance</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Produk Aktif</span>
                <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($activeProductsCount ?? 0) }} / {{ number_format($totalProducts ?? 0) }}</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Tersimpan di Database</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase">Rata-rata Margin Profit</span>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">+{{ number_format($avgMarginPercent ?? 0, 1) }}%</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Dihitung dari Modal vs Harga Jual</span>
            </div>
        </div>

        <!-- Form Pengaturan API Key Digiflazz -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-base font-extrabold text-slate-900 mb-1">Pengaturan API Key Digiflazz Production</h3>
            <p class="text-xs text-slate-500 mb-4">Ubah kredensial API Key dan keuntungan tanpa perlu merestart server Railway.</p>

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
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Markup Keuntungan (Rp)</label>
                        <input type="number" name="MARKUP_FLAT" value="{{ $markupFlat ?? 1500 }}" placeholder="Contoh: 1500" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-md transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Aksi Manual Sync -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <h4 class="font-bold text-slate-900 text-sm">Sinkronkan Produk Digiflazz Sekarang</h4>
                <p class="text-xs text-slate-500">Tarik harga modal & produk terbaru secara langsung dari Digiflazz.</p>
            </div>
            <form action="/admin/sync-now" method="POST">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-md">
                    ⚡ Sync Produk Sekarang
                </button>
            </form>
        </div>

    </main>
</body>
</html>
