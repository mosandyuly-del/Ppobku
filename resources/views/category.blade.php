<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan {{ $title }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Header / Navbar -->
    <nav class="bg-blue-600 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center max-w-4xl">
            <a href="/" class="text-xl font-bold tracking-wide">MOSANDY STORE</a>
            <a href="/" class="text-sm bg-white text-blue-600 px-3 py-1.5 rounded-lg font-semibold">&larr; Kembali</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Layanan {{ $title }}</h2>

        @php
            $slug = strtolower($title);
            $isMobileCategory = stristr($slug, 'PULSA') || stristr($slug, 'DATA');
        @endphp

        <!-- Form Input Nomor HP dengan Auto-Detect Operator -->
        @if($isMobileCategory)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 mb-6">
                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Handphone</label>
                <div class="relative">
                    <input type="tel" id="phoneNumber" placeholder="Contoh: 081234567890" autocomplete="off"
                        class="w-full pl-3 pr-28 py-3 border border-gray-300 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="detectOperator()">
                    <div id="operatorBadge" class="absolute right-3 top-2.5 hidden px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg uppercase">
                        -
                    </div>
                </div>
                <p id="operatorInfo" class="text-[11px] text-gray-400 mt-1">Masukkan nomor HP untuk mendeteksi operator otomatis.</p>
            </div>
        @endif

        <!-- Daftar Produk -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($products as $item)
                @php
                    $nama = $item->name ?? $item->nama ?? 'Produk PPOB';
                    $harga = $item->price ?? $item->harga ?? 0;
                    $brand = strtolower($item->brand ?? $item->category ?? 'umum');
                    $code = $item->code ?? $item->sku ?? $item->id;
                @endphp
                <div class="product-card bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center hover:shadow-md transition"
                     data-brand="{{ $brand }}"
                     data-name="{{ strtolower($nama) }}">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">{{ $nama }}</h3>
                        <p class="text-xs text-gray-500 uppercase">{{ $item->brand ?? $item->category }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-blue-600 font-bold text-sm block">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                        <button onclick="selectProduct('{{ $code }}', '{{ addslashes($nama) }}', '{{ $harga }}')" class="mt-1 text-xs bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-1.5 rounded-lg shadow">
                            Beli
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-6 rounded-xl text-center text-gray-500 shadow-sm">
                    <p class="font-semibold">Produk {{ $title }} Belum Tersedia</p>
                </div>
            @forelse
        </div>
    </div>

    <!-- Modal Form Pembelian / QRIS -->
    <div id="buyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="closeBuyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            
            <h3 class="text-lg font-bold text-gray-800 mb-1" id="modalProductName">Detail Pembelian</h3>
            <p class="text-xs text-gray-500 mb-4">Total: <span id="modalProductPrice" class="font-bold text-blue-600 text-sm"></span></p>

            <form action="/checkout" method="POST">
                @csrf
                <input type="hidden" name="product_code" id="modalProductCode">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Tujuan / ID Pelanggan</label>
                    <input type="text" name="target_no" id="modalTargetNo" required placeholder="Contoh: 081234567890" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Metode Pembayaran</label>
                    <div class="p-3 border rounded-lg bg-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-800">QRIS (Otomatis)</span>
                        <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">All E-Wallet / M-Banking</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow text-sm transition">
                    Lanjut Pembayaran QRIS &rarr;
                </button>
            </form>
        </div>
    </div>

    <script>
        const operatorPrefixes = {
            'telkomsel': ['0811','0812','0813','0821','0822','0823','0851','0852','0853'],
            'indosat': ['0814','0815','0816','0855','0856','0857','0858'],
            'xl': ['0817','0818','0819','0859','0877','0878'],
            'axis': ['0831','0832','0833','0838'],
            'tri': ['0895','0896','0897','0898','0899'],
            'smartfren': ['0881','0882','0883','0884','0885','0886','0887','0888','0889']
        };

        function detectOperator() {
            const input = document.getElementById('phoneNumber').value.trim();
            const badge = document.getElementById('operatorBadge');
            const info = document.getElementById('operatorInfo');
            const cards = document.querySelectorAll('.product-card');

            if (input.length >= 4) {
                const prefix = input.substring(0, 4);
                let detected = null;

                for (const [provider, prefixes] of Object.entries(operatorPrefixes)) {
                    if (prefixes.includes(prefix)) {
                        detected = provider;
                        break;
                    }
                }

                if (detected) {
                    badge.innerText = detected.toUpperCase();
                    badge.classList.remove('hidden');
                    info.innerText = `Operator terdeteksi: ${detected.toUpperCase()}`;

                    // Filter produk di layar sesuai provider
                    cards.forEach(card => {
                        const brand = card.getAttribute('data-brand');
                        const name = card.getAttribute('data-name');

                        if (brand.includes(detected) || name.includes(detected)) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    return;
                }
            }

            // Reset jika kurang dari 4 digit atau tidak ditemukan
            badge.classList.add('hidden');
            info.innerText = 'Masukkan nomor HP untuk mendeteksi operator otomatis.';
            cards.forEach(card => card.style.display = 'flex');
        }

        function selectProduct(code, name, price) {
            const phoneInput = document.getElementById('phoneNumber');
            const targetNo = phoneInput ? phoneInput.value : '';

            document.getElementById('modalProductCode').value = code;
            document.getElementById('modalProductName').innerText = name;
            document.getElementById('modalProductPrice').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            if (targetNo) {
                document.getElementById('modalTargetNo').value = targetNo;
            }
            document.getElementById('buyModal').classList.remove('hidden');
        }

        function closeBuyModal() {
            document.getElementById('buyModal').classList.add('hidden');
        }
    </script>
</body>
</html>
