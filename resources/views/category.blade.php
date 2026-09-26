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
            <div class="relative">
                <input type="tel" id="phoneNumber" placeholder="Masukkan nomor HP (08xxx)" 
                       value="{{ $phone ?? '' }}"
                       class="w-full p-3 pr-24 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                <span id="providerBadge" class="absolute right-3 top-2.5 text-[10px] font-black px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 hidden uppercase tracking-wider"></span>
            </div>
        </div>

        <div id="productList" class="space-y-2 pt-2">
            @forelse($products ?? [] as $product)
            <div class="product-item p-3 border border-slate-200 rounded-2xl flex justify-between items-center hover:border-blue-500 transition"
                 data-brand="{{ strtolower(($product->brand ?? '') . ' ' . ($product->name ?? '')) }}">
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

    <script>
        const phoneInput = document.getElementById('phoneNumber');
        const providerBadge = document.getElementById('providerBadge');
        const productItems = document.querySelectorAll('.product-item');

        const providerPrefixes = {
            'telkomsel': ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
            'indosat': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'xl': ['0817', '0818', '0819', '0859', '0877', '0878'],
            'axis': ['0831', '0832', '0833', '0838'],
            'three': ['0895', '0896', '0897', '0898', '0899'],
            'smartfren': ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889']
        };

        function filterProducts() {
            let num = phoneInput.value.replace(/[^0-9]/g, '');
            if (num.startsWith('62')) num = '0' + num.substring(2);

            let detected = '';
            if (num.length >= 4) {
                const prefix = num.substring(0, 4);
                for (let provider in providerPrefixes) {
                    if (providerPrefixes[provider].includes(prefix)) {
                        detected = provider;
                        break;
                    }
                }
            }

            if (detected) {
                providerBadge.innerText = detected.toUpperCase();
                providerBadge.classList.remove('hidden');
            } else {
                providerBadge.classList.add('hidden');
            }

            productItems.forEach(item => {
                const brand = item.getAttribute('data-brand') || '';
                let matchProvider = true;
                if (detected && num.length >= 4) {
                    if (detected === 'three') {
                        matchProvider = brand.includes('three') || brand.includes('tri');
                    } else {
                        matchProvider = brand.includes(detected);
                    }
                }

                if (matchProvider) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        phoneInput.addEventListener('input', filterProducts);
        filterProducts();
    </script>
</body>
</html>
