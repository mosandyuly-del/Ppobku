<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Integrasi Digiflazz - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

        <!-- Header Admin & Saldo Live Digiflazz -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Panel Kelola MOSANDY STORE</h1>
                <p class="text-xs text-slate-400 mt-1">Sistem Terintegrasi Digiflazz API Auto Fulfillment.</p>
            </div>

            <!-- Card Cek Saldo Realtime -->
            <div class="bg-blue-600 text-white p-4 rounded-2xl flex items-center space-x-4 shadow-md shadow-blue-500/20">
                <div>
                    <p class="text-[10px] uppercase font-black text-blue-200 tracking-wider">Saldo Deposit Digiflazz</p>
                    <p class="text-lg font-black mt-0.5">Rp {{ number_format($digiflazzBalance ?? 0, 0, ',', '.') }}</p>
                </div>
                <a href="/logout" class="bg-white/20 hover:bg-white/30 text-white font-bold text-xs px-3 py-2 rounded-xl transition">
                    Keluar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pengaturan Kredensial Digiflazz -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-extrabold text-slate-900 uppercase">Pengaturan API Digiflazz</h2>
            <form action="/admin/save-settings" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Username Digiflazz</label>
                    <input type="text" name="DIGIFLAZZ_USERNAME" value="{{ $digiflazzUsername }}" placeholder="Username Digiflazz" required 
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">API Key Production</label>
                    <input type="password" name="DIGIFLAZZ_KEY" value="{{ $digiflazzKey }}" placeholder="Key Production" required 
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keuntungan / Markup Flat (Rp)</label>
                    <input type="number" name="MARKUP_FLAT" value="{{ $markupFlat }}" placeholder="1500" required 
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-2.5 rounded-xl transition">
                        Simpan Kredensial Digiflazz
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi Masuk & Tombol Tembak Digiflazz -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-extrabold text-slate-900 uppercase">Daftar Transaksi Masuk (Proses Digiflazz)</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold border-b border-slate-200">
                            <th class="p-3">ID Transaksi</th>
                            <th class="p-3">Produk</th>
                            <th class="p-3">Nomor Tujuan</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi Digiflazz</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @forelse($recentTrx as $trx)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-blue-600">{{ $trx->trx_id }}</td>
                                <td class="p-3 font-bold text-slate-900">{{ $trx->product_name }}</td>
                                <td class="p-3 font-mono font-bold text-blue-600">{{ $trx->target_no }}</td>
                                <td class="p-3 font-black text-emerald-600">Rp {{ number_format($trx->price, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    @if($trx->status == 'SUCCESS')
                                        <span class="bg-emerald-100 text-emerald-800 font-black px-2.5 py-1 rounded-full text-[10px]">SUKSES</span>
                                    @elseif($trx->status == 'PENDING')
                                        <span class="bg-amber-100 text-amber-800 font-black px-2.5 py-1 rounded-full text-[10px]">MENUNGGU</span>
                                    @else
                                        <span class="bg-rose-100 text-rose-800 font-black px-2.5 py-1 rounded-full text-[10px]">GAGAL</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <form action="/admin/process-digiflazz" method="POST">
                                        @csrf
                                        <input type="hidden" name="trx_id" value="{{ $trx->trx_id }}">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-3 py-1.5 rounded-lg text-[10px] transition">
                                            ⚡ Tembak Ke Digiflazz
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-slate-400">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
