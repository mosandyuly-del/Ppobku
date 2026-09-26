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
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen p-4 space-y-4 shadow-sm">
        <div class="flex items-center space-x-3 border-b pb-3">
            <a href="/" class="text-slate-600 font-bold">&larr; Kembali</a>
            <h1 class="text-lg font-extrabold capitalize">{{ str_replace('-', ' ', $slug ?? 'Layanan') }}</h1>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-600">Nomor HP / Tujuan</label>
            <input type="tel" id="phoneNumber" placeholder="Masukkan nomor HP (08xxx)" 
                   value="{{ $phone ?? '' }}"
                   class="w-full p-3 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div id="productList" class="space-y-2 pt-2">
            @forelse($products ?? [] as $product)
            <div class="p-3 border border-slate-200 rounded-2xl flex justify-between items-center hover:border-blue-500 transition">
                <div>
                    <p class="text-xs font-extrabold text-slate-900">{{ $product->name }}</p>
                    <p class="text-[10px] text-slate-500">{{ $product->description ?? 'Proses Otomatis 24 Jam' }}</p>
                    <span class="text-[10px] bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded-full inline-block mt-1">{{ $product->brand ?? 'PPOB' }}</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-blue-600">Rp {{ number_format($product->price_sell ?? $product->price ?? 0, 0, ',', '.') }}</p>
                    <a href="/checkout/{{ $product->id }}" class="inline-block mt-1 bg-blue-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-sm">Beli</a>
                </div>
            </div>
            @empty
            <p class="text-xs text-center text-slate-400 py-6">Tidak ada produk tersedia.</p>
            @endforelse
        </div>
    </div>

</body>
</html>
