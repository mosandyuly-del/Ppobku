<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSANDY STORE - Layanan PPOB & Top Up Tercepat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .active-scale:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <!-- Container Aplikasi Mobile -->
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative pb-20">
        
        <!-- Header Banner Modern -->
        <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-6 rounded-b-[2.5rem] text-white shadow-lg space-y-5">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Selamat Datang di</p>
                    <h1 class="text-xl font-extrabold tracking-tight">MOSANDY STORE ✨</h1>
                </div>
                <a href="/cek-pesanan" class="glass-card text-blue-900 text-xs font-extrabold px-3.5 py-2 rounded-full shadow-sm active-scale transition">
                    Cek Pesanan
                </a>
            </div>

            <!-- Card Saldo / Banner Promosi -->
            <div class="glass-card rounded-2xl p-4 text-slate-800 shadow-md border border-white/40 space-y-2">
                <div class="flex justify-between items-center text-xs text-slate-500 font-medium">
                    <span>Layanan Otomatis</span>
                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online 24/7
                    </span>
                </div>
                <p class="text-sm font-extrabold text-slate-900">Isi Pulsa, Data & Token Listrik Termurah</p>
            </div>
        </div>

        <!-- Grid Layanan Utama -->
        <div class="p-5 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-extrabold text-slate-900 tracking-tight">Pilih Layanan Top Up</h2>
                <span class="text-[11px] font-bold text-blue-600">Serba Otomatis</span>
            </div>

            <div class="grid grid-cols-4 gap-4">
                <!-- Pulsa -->
                <a href="/kategori/pulsa" class="flex flex-col items-center space-y-2 group active-scale transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700">Pulsa</span>
                </a>

                <!-- Paket Data -->
                <a href="/kategori/paket-data" class="flex flex-col items-center space-y-2 group active-scale transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700">Paket Data</span>
                </a>

                <!-- Token PLN -->
                <a href="/kategori/pln" class="flex flex-col items-center space-y-2 group active-scale transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center text-white shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700">Token PLN</span>
                </a>

                <!-- Cek IP -->
                <a href="/cek-ip" class="flex flex-col items-center space-y-2 group active-scale transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700">Cek IP</span>
                </a>
            </div>
        </div>

        <!-- Navigation Bar Bawah -->
        <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-slate-200 py-2.5 px-6 flex justify-around items-center z-50">
            <a href="/" class="flex flex-col items-center text-blue-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l1.293 1.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                <span class="text-[10px] font-extrabold mt-0.5">Beranda</span>
            </a>
            <a href="/cek-ip" class="flex flex-col items-center text-slate-400 hover:text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span class="text-[10px] font-bold mt-0.5">Cek IP</span>
            </a>
            <a href="/cek-pesanan" class="flex flex-col items-center text-slate-400 hover:text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-[10px] font-bold mt-0.5">Pesanan</span>
            </a>
        </div>

    </div>

</body>
</html>
