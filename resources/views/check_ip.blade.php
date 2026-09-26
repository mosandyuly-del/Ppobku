<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek IP Publik - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen p-5 space-y-5 shadow-2xl relative">
        
        <!-- Header Nav -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <a href="/" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold">
                &larr;
            </a>
            <h1 class="text-base font-extrabold text-slate-900 tracking-tight">Informasi IP & Jaringan</h1>
            <div class="w-9"></div>
        </div>

        <!-- Box Tampilan IP Utama -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 text-white text-center space-y-2 shadow-lg shadow-blue-500/20">
            <p class="text-xs font-semibold text-blue-200 uppercase tracking-widest">Alamat IP Anda Saat Ini</p>
            <p class="text-2xl font-black tracking-wider bg-white/10 py-2.5 px-4 rounded-2xl backdrop-blur-md border border-white/20 inline-block w-full select-all">
                {{ $ip }}
            </p>
            <span class="inline-block text-[10px] bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 px-3 py-1 rounded-full font-extrabold">
                ● Terdeteksi Aktif
            </span>
        </div>

        <!-- Detail Informasi Jaringan -->
        <div class="space-y-3 pt-2">
            <p class="text-xs font-extrabold text-slate-800">Detail Penyedia Jaringan (ISP)</p>

            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">ISP / Provider:</span>
                    <span class="font-extrabold text-slate-800">{{ $details['isp'] ?? ($details['org'] ?? 'Tidak Terdeteksi') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Kota / Wilayah:</span>
                    <span class="font-extrabold text-slate-800">{{ $details['city'] ?? '-' }}, {{ $details['regionName'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Negara:</span>
                    <span class="font-extrabold text-slate-800">{{ $details['country'] ?? 'Indonesia' }} ({{ $details['countryCode'] ?? 'ID' }})</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-medium">Zona Waktu:</span>
                    <span class="font-extrabold text-slate-800">{{ $details['timezone'] ?? 'Asia/Jakarta' }}</span>
                </div>
            </div>
        </div>

        <!-- User Agent -->
        <div class="space-y-2">
            <p class="text-xs font-extrabold text-slate-800">Informasi Perangkat & Browser</p>
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 text-[11px] text-slate-600 font-semibold break-words">
                {{ $userAgent }}
            </div>
        </div>

        <!-- Tombol Salin IP -->
        <button onclick="navigator.clipboard.writeText('{{ $ip }}'); alert('IP Berhasil Disalin!');" 
                class="w-full bg-slate-900 text-white font-extrabold py-3.5 rounded-2xl shadow-md text-xs active:scale-95 transition">
            📋 Salin Alamat IP
        </button>

    </div>

</body>
</html>
