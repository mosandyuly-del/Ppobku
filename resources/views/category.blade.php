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
            <h1 class="text-lg font-extrabold capitalize">{{ str_replace('-', ' ', $slug) }}</h1>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-600">Nomor HP / Tujuan</label>
            <div class="relative">
                <input type="tel" id="phoneNumber" placeholder="Masukkan nomor HP (08xxx)" 
                       value="{{ $phone ?? '' }}"
                       class="w-full p-3 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                <span id="operatorBadge" class="absolute right-3 top-3 text-xs font-black px-2 py-1 rounded bg-blue-100 text-blue-700 hidden"></span>
            </div>
        </div>

        @if($showExpiryFilter)
        <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl space-y-1">
            <label class="text-xs font-bold text-amber-900">Filter Masa Aktif Data</label>
            <select id="expiryFilter" class="w-full p-2 text-xs rounded-lg border border-amber-300 bg-white font-semibold">
                <option value="">Semua Masa Aktif</option>
                <option value="1">1 Hari</option>
                <option value="3">3 Hari</option>
                <option value="7">7 Hari</option>
                <option value="30">30 Hari</option>
            </select>
        </div>
        @endif

        <div id="productList" class="space-y-2 pt-2">
            @forelse($products as $product)
            <div class="product-item p-3 border border-slate-200 rounded-2xl flex justify-between items-center hover:border-blue-500 transition"
                 data-brand="{{ strtolower($product->brand ?? '') }}"
                 data-expiry="{{ $product->expiry_days ?? '' }}">
                <div>
                    <p class="text-xs font-extrabold text-slate-900">{{ $product->name }}</p>
                    <p class="text-[10px] text-slate-500">{{ $product->description ?? 'Proses Otomatis 24 Jam' }}</p>
                    <span class="text-[10px] bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded-full inline-block mt-1">{{ $product->brand }}</span>
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
        const operatorBadge = document.getElementById('operatorBadge');
        const productItems = document.querySelectorAll('.product-item');
        const expiryFilter = document.getElementById('expiryFilter');

        const prefixes = {
            'telkomsel': ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
            'indosat': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'xl': ['0817', '0818', '0819', '0859', '0877', '0878'],
            'axis': ['0831', '0832', '0833', '0838'],
            'tri': ['0895', '0896', '0897', '0898', '0899'],
            'smartfren': ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889']
        };

        function filterProducts() {
            let val = phoneInput.value.replace(/[^0-9]/g, '');
            if (val.startsWith('62')) val = '0' + val.substring(2);
            
            let detectedBrand = '';
            if (val.length >= 4) {
                const prefix = val.substring(0, 4);
                for (let brand in prefixes) {
                    if (prefixes[brand].includes(prefix)) {
                        detectedBrand = brand;
                        break;
                    }
                }
            }

            if (detectedBrand) {
                operatorBadge.innerText = detectedBrand.toUpperCase();
                operatorBadge.classList.remove('hidden');
            } else {
                operatorBadge.classList.add('hidden');
            }

            const selectedExpiry = expiryFilter ? expiryFilter.value : '';

            productItems.forEach(item => {
                const itemBrand = item.getAttribute('data-brand');
                const itemExpiry = item.getAttribute('data-expiry');

                let matchBrand = !detectedBrand || itemBrand.includes(detectedBrand);
                let matchExpiry = !selectedExpiry || itemExpiry === selectedExpiry;

                if (matchBrand && matchExpiry) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        phoneInput.addEventListener('input', filterProducts);
        if (expiryFilter) expiryFilter.addEventListener('change', filterProducts);
    </script>
</body>
</html>
