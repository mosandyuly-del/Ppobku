<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Produk - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .active-scale:active { transform: scale(0.98); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen p-5 space-y-5 shadow-2xl relative">
        
        <!-- Top Nav -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <a href="/" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold active-scale transition">
                &larr;
            </a>
            <h1 class="text-base font-extrabold capitalize text-slate-900 tracking-tight">
                {{ str_replace('-', ' ', $slug ?? 'Layanan') }}
            </h1>
            <div class="w-9"></div>
        </div>

        <!-- Input Nomor Tujuan Modern -->
        <div class="space-y-1.5">
            <label class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Nomor Tujuan / ID</label>
            <div class="relative">
                <input type="tel" id="phoneNumber" placeholder="Masukkan nomor (08xxx)" 
                       value="{{ $phone ?? '' }}"
                       class="w-full p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-sm font-extrabold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
        </div>

        <!-- Daftar Card Produk Aesthetic -->
        <div class="space-y-3 pt-2">
            <p class="text-xs font-extrabold text-slate-800">Pilihan Nominal Available</p>

            @forelse($products ?? [] as $product)
            <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-2xl flex justify-between items-center hover:border-blue-500 hover:bg-white transition shadow-sm hover:shadow-md">
                <div class="space-y-1 max-w-[65%]">
                    <p class="text-xs font-extrabold text-slate-900 leading-snug">{{ $product->name }}</p>
                    <p class="text-[10px] text-slate-400 font-medium line-clamp-1">{{ $product->description ?? 'Proses Kilat 24 Jam Online' }}</p>
                    <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md inline-block uppercase">
                        {{ $product->brand ?? 'PPOB' }}
                    </span>
                </div>
                <div class="text-right space-y-1.5">
                    <p class="text-sm font-black text-blue-600">Rp {{ number_format($product->price_sell ?? $product->price ?? 0, 0, ',', '.') }}</p>
                    <a href="/checkout/{{ $product->id }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-xl shadow-md shadow-blue-500/20 active-scale transition">
                        Beli Now
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-10 space-y-2">
                <p class="text-xs text-slate-400 font-bold">Belum ada produk untuk kategori ini.</p>
            </div>
            @endforelse
        </div>

    </div>

</body>
</html>
