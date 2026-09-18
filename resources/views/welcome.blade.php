<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSANDY STORE - PPOB & Digital Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-blue-900 text-white text-[11px] font-semibold py-2 px-4 shadow-inner">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2 truncate">
                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">LIVE</span>
                <span class="opacity-90">Sistem Otomatis 24 Jam Nonstop • QRIS All Payment • Proses Instan 1-3 Detik</span>
            </div>
            <a href="/cek-pesanan" class="hidden sm:inline-flex items-center space-x-1 text-blue-200 hover:text-white transition font-bold">
                <span>Lacak Pesanan &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Header Navbar -->
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3.5 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md">
                    M
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">MOSANDY <span class="text-blue-600">STORE</span></h1>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">PPOB & Digital Marketplace</span>
                </div>
            </a>
            <div class="flex items-center space-x-2">
                <a href="/cek-pesanan" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl font-bold transition">🔍 Lacak Pesanan</a>
                <a href="/admin" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold transition shadow-sm">Admin Panel</a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- Hero Banner -->
        <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-block bg-white/20 text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">Layanan Terlengkap</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang di MOSANDY STORE</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl font-medium">Platform pengisian Pulsa, Paket Data, Voucher Game, Token Listrik PLN, dan Pembayaran Tagihan PPOB Tercepat 24 Jam.</p>
            </div>
        </div>

        <!-- Grid Menu Layanan Lengkap -->
        <h3 class="font-extrabold text-slate-900 text-base mb-4 uppercase tracking-wider">Pilih Layanan Digital</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            
            <a href="/category/pulsa" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    📱
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Pulsa Reguler</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Semua Operator</p>
            </a>

            <a href="/category/data" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    🌐
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Paket Data</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Kuota Harian & Bulanan</p>
            </a>

            <a href="/category/game" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    🎮
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Voucher Game</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">MLBB, FF, PUBG, DLL</p>
            </a>

            <a href="/category/pln-token" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    ⚡
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Token PLN</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Listrik Prabayar</p>
            </a>

            <a href="/category/pln-bill" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    💡
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Tagihan PLN</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Listrik Pascabayar</p>
            </a>

            <a href="/category/pdam" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    💧
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Air PDAM</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Cek & Bayar Tagihan</p>
            </a>

            <a href="/category/e-money" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    💸
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Saldo E-Wallet</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Dana, GoPay, OVO, Shopee</p>
            </a>

            <a href="/cek-pesanan" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:border-blue-500 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition">
                    🔍
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Lacak Pesanan</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Cek Status Transaksi</p>
            </a>

        </div>
    </main>

    <!-- Floating WhatsApp Customer Service -->
    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20MOSANDY%20STORE,%20saya%20butuh%20bantuan" target="_blank" class="fixed bottom-5 right-5 bg-emerald-500 text-white p-3.5 rounded-full shadow-2xl flex items-center justify-center hover:bg-emerald-600 transition z-50">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
    </a>
</body>
</html>
