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
            $isDataCategory = stristr($slug, 'DATA');
        @endphp

        <!-- Form Input Nomor HP dengan Auto-Detect Operator -->
        @if($isMobileCategory)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 mb-6">
                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Handphone</label>
                <div class="relative">
                    <input type="tel" id="phoneNumber" placeholder="Contoh: 081234567890" autocomplete="off"
                        class="w-full pl-3 pr-28 py-3 border border-gray-300 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="filterProducts()">
                    <div id="operatorBadge" class="absolute right-3 top-2.5 hidden px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg uppercase">
                        -
                    </div>
                </div>
                <p id="operatorInfo" class="text-[11px] text-gray-400 mt-1">Masukkan nomor HP untuk mendeteksi operator otomatis.</p>
                
                <!-- Filter Pilihan Durasi Khusus Paket Data -->
                @if($isDataCategory)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Masa Aktif / Durasi Paket</label>
                        <div class="flex flex-wrap gap-2" id="durationFilters">
                            <button type="button" onclick="setDuration('all')" class="duration-btn bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg border border-blue-600 shadow-sm" data-duration="all">
                                Semua Masa Aktif
                            </button>
                            <button type="button" onclick="setDuration('harian')" class="duration-btn bg-gray-50 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100" data-duration="harian">
                                Harian (1-6 Hari)
                            </button>
                            <button type="button" onclick="setDuration('mingguan')" class="duration-btn bg-gray-50 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100" data-duration="mingguan">
                                Mingguan (7 Hari)
                            </button>
                            <button type="button" onclick="setDuration('dwimingguan')" class="duration-btn bg-gray-50 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100" data-duration="dwimingguan">
                                Dwi Mingguan (14-15 Hari)
                            </button>
                            <button type="button" onclick="setDuration('bulanan')" class="duration-btn bg-gray-50 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100" data-duration="bulanan">
                                Bulanan (28-30 Hari)
                            </button>
                        </div>
                    </div>
                @endif
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
            @endforelse
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
        let selectedDuration = 'all';

        const operatorPrefixes = {
            'telkomsel': ['0811','0812','0813','0821','0822','0823','0851','0852','0853'],
            'indosat': ['0814','0815','0816','0855','0856','0857','0858'],
            'xl': ['0817','0818','0819','0859','0877','0878'],
            'axis': ['0831','0832','0833','0838'],
            'tri': ['0895','0896','0897','0898','0899'],
            'smartfren': ['0881','0882','0883','0884','0885','0886','0887','0888','0889']
        };

        function setDuration(duration) {
            selectedDuration = duration;

            document.querySelectorAll('.duration-btn').forEach(btn => {
                if (btn.getAttribute('data-duration') === duration) {
                    btn.className = 'duration-btn bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg border border-blue-600 shadow-sm';
                } else {
                    btn.className = 'duration-btn bg-gray-50 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100';
                }
            });

            filterProducts();
        }

        function filterProducts() {
            const phoneInput = document.getElementById('phoneNumber');
            const input = phoneInput ? phoneInput.value.trim() : '';
            const badge = document.getElementById('operatorBadge');
            const info = document.getElementById('operatorInfo');
            const cards = document.querySelectorAll('.product-card');

            let detectedProvider = null;

            if (input.length >= 4) {
                const prefix = input.substring(0, 4);
                for (const [provider, prefixes] of Object.entries(operatorPrefixes)) {
                    if (prefixes.includes(prefix)) {
                        detectedProvider = provider;
                        break;
                    }
                }
            }

            if (detectedProvider && badge && info) {
                badge.innerText = detectedProvider.toUpperCase();
                badge.classList.remove('hidden');
                info.innerText = `Operator terdeteksi: ${detectedProvider.toUpperCase()}`;
            } else if (badge && info) {
                badge.classList.add('hidden');
                info.innerText = 'Masukkan nomor HP untuk mendeteksi operator otomatis.';
            }

            cards.forEach(card => {
                const brand = card.getAttribute('data-brand');
                const name = card.getAttribute('data-name');

                // Match Operator
                let matchOperator = true;
                if (detectedProvider) {
                    matchOperator = brand.includes(detectedProvider) || name.includes(detectedProvider);
                }

                // Match Duration
                let matchDuration = true;
                if (selectedDuration === 'harian') {
                    matchDuration = name.includes('1 hari') || name.includes('2 hari') || name.includes('3 hari') || name.includes('4 hari') || name.includes('5 hari') || name.includes('6 hari') || (name.includes('hari') && !name.includes('7 hari') && !name.includes('14 hari') && !name.includes('15 hari') && !name.includes('28 hari') && !name.includes('29 hari') && !name.includes('30 hari'));
                } else if (selectedDuration === 'mingguan') {
                    matchDuration = name.includes('7 hari') || name.includes('minggu');
                } else if (selectedDuration === 'dwimingguan') {
                    matchDuration = name.includes('14 hari') || name.includes('15 hari') || name.includes('2 minggu');
                } else if (selectedDuration === 'bulanan') {
                    matchDuration = name.includes('28 hari') || name.includes('29 hari') || name.includes('30 hari') || name.includes('bulan') || name.includes('30d');
                }

                if (matchOperator && matchDuration) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
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
