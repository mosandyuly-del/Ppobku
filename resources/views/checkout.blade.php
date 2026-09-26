<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Produk - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative p-5 space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <a href="javascript:history.back()" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold">&larr;</a>
            <h1 class="text-base font-extrabold text-slate-900">Konfirmasi Pesanan</h1>
            <div class="w-9"></div>
        </div>

        <!-- Detail Rincian Produk -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-5 space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-wider">
                        {{ $product->brand ?? 'PPOB' }}
                    </span>
                    <h2 class="text-sm font-extrabold text-slate-900 mt-2">{{ $product->name }}</h2>
                </div>
            </div>
            
            <div class="border-t border-slate-200/60 pt-3 flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500">Harga Produk:</span>
                <span class="text-sm font-black text-blue-600">Rp {{ number_format($product->price_sell, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Form Nomor Tujuan & Pilih Pembayaran -->
        <form action="/checkout" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="space-y-1.5">
                <label class="text-xs font-extrabold text-slate-800">Nomor Tujuan / No. HP Pelanggan</label>
                <input type="tel" name="phone" value="{{ $phone }}" required placeholder="Contoh: 081234567890" 
                       class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-black text-slate-900 focus:outline-none focus:border-blue-600">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-extrabold text-slate-800">Metode Pembayaran</label>
                
                <div class="p-4 border-2 border-blue-600 bg-blue-50/40 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-xs text-blue-700 shadow-sm border border-slate-200">
                            QRIS
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-900">QRIS Mosandy Cell</p>
                            <p class="text-[10px] text-slate-500 font-semibold">Bisa Scan via DANA, OVO, ShopeePay, m-Banking</p>
                        </div>
                    </div>
                    <span class="text-blue-600 font-black">✓</span>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold py-4 rounded-2xl text-xs shadow-lg shadow-blue-500/20 active:scale-95 transition">
                Lanjut ke Pembayaran QRIS &rarr;
            </button>
        </form>

    </div>

</body>
</html>
