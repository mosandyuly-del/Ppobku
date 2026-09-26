<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl p-5 space-y-6 pb-10">
        
        <!-- Header Nav -->
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <div>
                <p class="text-xs font-semibold text-slate-400">Panel Kontrol Admin</p>
                <h1 class="text-lg font-extrabold text-slate-900">MOSANDY STORE ⚙️</h1>
            </div>
            <form action="/admin/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-50 text-red-600 text-xs font-extrabold px-3.5 py-2 rounded-xl active:scale-95 transition">
                    Logout
                </button>
            </form>
        </div>

        <!-- Alert Notifikasi -->
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-3 rounded-2xl">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs font-bold p-3 rounded-2xl">
            {{ session('error') }}
        </div>
        @endif

        <!-- Card Widget Cek IP Server Railway -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-4 text-white space-y-2 shadow-lg">
            <div class="flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                <span>🌐 IP Outbound Server Railway</span>
                <span class="text-emerald-400 font-extrabold">Aktif</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-lg font-black text-emerald-400 tracking-wider select-all">{{ $serverIp }}</span>
                <button onclick="navigator.clipboard.writeText('{{ $serverIp }}'); alert('IP Server Berhasil Disalin!');" 
                        class="bg-white/10 hover:bg-white/20 text-white text-[10px] font-extrabold px-3 py-1.5 rounded-xl border border-white/10">
                    📋 Salin IP
                </button>
            </div>
            <p class="text-[10px] text-slate-400">Gunakan IP ini untuk di-whitelist pada Dashboard Member Digiflazz.</p>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl space-y-1">
                <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">Total Produk</p>
                <p class="text-2xl font-black text-blue-900">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl space-y-1">
                <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider">Total Pesanan</p>
                <p class="text-2xl font-black text-indigo-900">{{ number_format($totalOrders) }}</p>
            </div>
        </div>

        <!-- Tabel Pesanan Masuk & Tombol Tembak Digiflazz -->
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <p class="text-xs font-extrabold text-slate-800">Daftar Transaksi Masuk</p>
                <a href="/" class="text-[11px] font-bold text-blue-600">&larr; Ke Toko</a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-black text-xs text-slate-900">{{ $order->trx_id }}</p>
                            <p class="text-[11px] text-blue-600 font-extrabold">{{ $order->phone }}</p>
                            <p class="text-[10px] text-slate-500 font-semibold">{{ $order->product_name }}</p>
                        </div>
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full 
                            {{ $order->status == 'Sukses' ? 'bg-emerald-100 text-emerald-700' : ($order->status == 'Gagal' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    @if(!empty($order->sn))
                    <div class="text-[10px] bg-slate-200/60 p-2 rounded-xl text-slate-700 font-mono break-all">
                        <strong>SN:</strong> {{ $order->sn }}
                    </div>
                    @endif

                    <!-- Tombol Tembak Orderan -->
                    <form action="/admin/orders/{{ $order->id }}/process" method="POST" class="pt-1">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 active:scale-95 transition flex items-center justify-center gap-1">
                            <span>⚡</span> Tembak ke Digiflazz
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-8 text-xs text-slate-400 font-semibold bg-slate-50 border border-slate-100 rounded-2xl">
                    Belum ada transaksi masuk dari pembeli.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</body>
</html>
