<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Produk - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative pb-10">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 p-5 text-white flex items-center justify-between">
            <a href="/" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold">&larr;</a>
            <h1 class="text-base font-extrabold capitalize">Layanan {{ str_replace('-', ' ', $slug) }}</h1>
            <div class="w-8"></div>
        </div>

        <div class="p-4 space-y-4">
            <!-- Form Input Nomor HP dengan Auto Detect -->
            <form action="" method="GET" class="space-y-2">
                <label class="text-xs font-extrabold text-slate-700">Nomor Tujuan / No. Pelanggan</label>
                <div class="relative">
                    <input type="tel" name="phone" id="phoneInput" value="{{ $phone }}" 
                           placeholder="Masukkan nomor (misal: 0812xxxx)" 
                           oninput="if(this.value.length >= 4) { this.form.submit(); }"
                           class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-extrabold text-slate-900 focus:outline-none focus:border-blue-600">
                    
                    @if(!empty($detectedBrand))
                    <span class="absolute right-3 top-2.5 bg-blue-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                        {{ $detectedBrand }}
                    </span>
                    @endif
                </div>
            </form>

            @if(!empty($detectedBrand))
            <div class="p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold rounded-xl flex items-center gap-1.5">
                <span>⚡</span> Terdeteksi Operator: <strong class="uppercase">{{ $detectedBrand }}</strong>
            </div>
            @endif

            <!-- List Produk -->
            <div class="space-y-2.5 pt-2">
                <p class="text-xs font-extrabold text-slate-800">Pilihan Produk Tersedia</p>

                @forelse($products as $p)
                <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-2xl flex justify-between items-center hover:border-blue-300 transition">
                    <div class="space-y-1">
                        <p class="text-xs font-extrabold text-slate-900">{{ $p->name }}</p>
                        <p class="text-[10px] text-slate-500 font-semibold uppercase">{{ $p->brand }} • {{ $p->buyer_sku_code }}</p>
                    </div>
                    <div class="text-right space-y-1">
                        <p class="text-xs font-black text-blue-600">Rp {{ number_format($p->price_sell, 0, ',', '.') }}</p>
                        <a href="/checkout/{{ $p->id }}?phone={{ $phone }}" class="inline-block bg-blue-600 text-white text-[10px] font-extrabold px-3 py-1.5 rounded-xl shadow-sm">
                            Beli
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 space-y-1">
                    <p class="text-xs font-extrabold text-slate-500">Tidak ada produk yang cocok</p>
                    <p class="text-[10px] text-slate-400">Coba periksa kembali nomor atau pilihan layanan Anda.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</body>
</html>
