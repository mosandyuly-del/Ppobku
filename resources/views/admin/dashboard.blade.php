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

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl p-5 space-y-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <div>
                <p class="text-xs font-semibold text-slate-400">Panel Kontrol</p>
                <h1 class="text-lg font-extrabold text-slate-900">Dashboard Admin ⚙️</h1>
            </div>
            <form action="/admin/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-50 text-red-600 text-xs font-extrabold px-3.5 py-2 rounded-xl active:scale-95 transition">
                    Logout
                </button>
            </form>
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

        <!-- Quick Action Tools -->
        <div class="space-y-2">
            <p class="text-xs font-extrabold text-slate-800">Aksi Cepat Admin</p>
            <div class="grid grid-cols-2 gap-2">
                <a href="/cek-ip" class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-extrabold text-slate-700 flex items-center justify-between">
                    <span>🌐 Cek IP Server</span>
                    <span>&rarr;</span>
                </a>
                <a href="/" class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-extrabold text-slate-700 flex items-center justify-between">
                    <span>🛒 Ke Halaman Depan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Tabel Pesanan Terbaru -->
        <div class="space-y-3">
            <p class="text-xs font-extrabold text-slate-800">10 Pesanan Terakhir</p>
            
            <div class="space-y-2">
                @forelse($recentOrders as $order)
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl flex justify-between items-center text-xs">
                    <div>
                        <p class="font-extrabold text-slate-900">{{ $order->trx_id }}</p>
                        <p class="text-[10px] text-slate-500 font-semibold">{{ $order->phone }} - {{ $order->product_name }}</p>
                    </div>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                        {{ $order->status }}
                    </span>
                </div>
                @empty
                <div class="text-center py-6 text-xs text-slate-400 font-semibold">
                    Belum ada transaksi masuk.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</body>
</html>
