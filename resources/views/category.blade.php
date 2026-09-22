<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Katalog Produk' }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2.5">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-black text-white">M</div>
                <div>
                    <h1 class="font-extrabold text-sm text-slate-900 leading-none">MOSANDY STORE</h1>
                    <span class="text-[10px] text-slate-400 font-semibold">PPOB & DIGITAL MARKETPLACE</span>
                </div>
            </a>
            <div class="flex items-center space-x-2">
                <a href="/cek-pesanan" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg">Lacak</a>
                <a href="/" class="text-xs bg-blue-50 text-blue-600 font-bold px-3 py-1.5 rounded-lg hover:bg-blue-100">&larr; Kembali</a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto w-full px-4 py-6 space-y-5">

        @if(session('error'))
            <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Banner Judul Kategori -->
        <div class="bg-blue-600 p-6 rounded-3xl text-white shadow-lg shadow-blue-500/20">
            <span class="text-[10px] font-black uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full">Katalog Digital</span>
            <h2 class="text-2xl font-black mt-2">{{ $title }}</h2>
            <p class="text-xs text-blue-100 mt-1">Masukkan nomor HP tujuan untuk mendeteksi provider dan menampilkan paket secara otomatis.</p>
        </div>

        <!-- Input Nomor HP Utama untuk Auto Detection -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200 space-y-3">
            <label class="block text-xs font-extrabold text-slate-700 uppercase">Nomor HP Tujuan</label>
            <div class="relative">
                <input type="tel" id="input_phone" placeholder="Contoh: 081234567890" autofocus
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-extrabold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <div id="operator_badge" class="absolute right-3 top-3 hidden">
                    <span id="operator_name" class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-blue-100 text-blue-700">TELKOMSEL</span>
                </div>
            </div>
            <p id="phone_hint" class="text-[11px] font-semibold text-slate-400">Masukkan minimal 4 digit nomor HP untuk memunculkan produk spesifik provider.</p>
        </div>

        <!-- Filter Masa Aktif / Durasi Paket (Harian, Mingguan, Bulanan) -->
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-xs font-extrabold text-slate-700 uppercase">Masa Aktif / Durasi Paket</span>
                <span id="filter_count" class="text-[10px] font-bold text-slate-400">Menampilkan Semua</span>
            </div>
            <div class="flex space-x-2 overflow-x-auto no-scrollbar py-1">
                <button onclick="filterDuration('all')" class="duration-btn active bg-blue-600 text-white font-extrabold text-xs px-4 py-2 rounded-xl transition shrink-0 shadow-sm" data-duration="all">
                    Semua Masa Aktif
                </button>
                <button onclick="filterDuration('harian')" class="duration-btn bg-white border border-slate-200 hover:border-blue-400 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition shrink-0 shadow-sm" data-duration="harian">
                    ⚡ Harian (1-3 Hari)
                </button>
                <button onclick="filterDuration('mingguan')" class="duration-btn bg-white border border-slate-200 hover:border-blue-400 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition shrink-0 shadow-sm" data-duration="mingguan">
                    📅 Mingguan (7 Hari)
                </button>
                <button onclick="filterDuration('bulanan')" class="duration-btn bg-white border border-slate-200 hover:border-blue-400 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition shrink-0 shadow-sm" data-duration="bulanan">
                    📆 Bulanan (30 Hari)
                </button>
            </div>
        </div>

        <!-- Filter Search Kata Kunci -->
        <div>
            <input type="text" id="search_product" placeholder="🔍 Cari nama paket (misal: 1 GB, Combo, Freedom, OMG)..." 
                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        <!-- Grid Produk -->
        <div id="product_grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($products as $p)
                @php
                    $brandLower = strtolower($p->brand ?? 'umum');
                    $nameLower = strtolower($p->name ?? '');
                    $descLower = strtolower($p->description ?? '');
                    $fullText = $brandLower . ' ' . $nameLower . ' ' . $descLower;
                @endphp
                <div class="product-card bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-blue-300 transition"
                    data-brand="{{ $brandLower }}"
                    data-fulltext="{{ $fullText }}">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md uppercase">{{ $p->brand ?? 'PPOB' }}</span>
                            <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md">Proses Instan</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $p->name }}</h3>
                        @if(!empty($p->description))
                            <p class="text-[11px] text-slate-400 mt-1 leading-snug">{{ $p->description }}</p>
                        @endif
                        <p class="text-xs font-black text-blue-600 mt-2">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    </div>

                    <button onclick="openCheckout('{{ $p->code ?? $p->sku }}', '{{ addslashes($p->name) }}', '{{ $p->price }}')" 
                        class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold py-2.5 rounded-xl shadow-md transition text-center">
                        Beli Sekarang &rarr;
                    </button>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-3xl border border-slate-200 text-center">
                    <p class="text-xs text-slate-400 font-bold">Produk belum diisi di database. Silakan tambah produk di Admin Panel.</p>
                </div>
            @endforelse
        </div>

        <div id="not_found_state" class="hidden bg-white p-8 rounded-3xl border border-dashed border-slate-300 text-center space-y-2">
            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto text-slate-400 font-bold text-xl">🔍</div>
            <h3 class="font-extrabold text-slate-800 text-sm">Produk Tidak Ditemukan</h3>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">Tidak ada paket yang sesuai dengan filter atau nomor HP ini.</p>
        </div>

    </main>

    <!-- Modal Konfirmasi Checkout -->
    <div id="checkoutModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white max-w-sm w-full p-6 rounded-3xl shadow-2xl border border-slate-100 space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full uppercase">Konfirmasi Pembelian</span>
                <button onclick="closeCheckout()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form action="/checkout" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_code" id="modal_product_code">

                <div>
                    <h3 id="modal_product_name" class="font-extrabold text-slate-900 text-base leading-snug">Nama Produk</h3>
                    <p class="text-xs text-slate-500 mt-1">Total Tagihan: <span id="modal_product_price" class="font-black text-blue-600">Rp 0</span></p>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase mb-1">Nomor Tujuan</label>
                    <input type="text" name="target_no" id="modal_target_no" required readonly
                        class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-lg shadow-blue-500/25 transition">
                    Lanjut Pembayaran &rarr;
                </button>
            </form>
        </div>
    </div>

    <!-- Script Auto Detection + Durasi Filter Logic -->
    <script type="text/javascript">
        const prefixMap = {
            'telkomsel': ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
            'indosat': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'xl': ['0817', '0818', '0819', '0859', '0877', '0878'],
            'axis': ['0831', '0832', '0833', '0838'],
            'tri': ['0895', '0896', '0897', '0898', '0899'],
            'smartfren': ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889']
        };

        const phoneInput = document.getElementById('input_phone');
        const searchInput = document.getElementById('search_product');
        const productCards = document.querySelectorAll('.product-card');
        const notFoundState = document.getElementById('not_found_state');
        const operatorBadge = document.getElementById('operator_badge');
        const operatorName = document.getElementById('operator_name');
        const durationBtns = document.querySelectorAll('.duration-btn');

        let detectedBrand = null;
        let selectedDuration = 'all';

        function applyFilters() {
            let keyword = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            productCards.forEach(card => {
                let cardBrand = card.getAttribute('data-brand');
                let fulltext = card.getAttribute('data-fulltext');

                // Match Brand dari Auto Detect No HP
                let matchBrand = true;
                if (detectedBrand) {
                    matchBrand = (cardBrand === detectedBrand) || fulltext.includes(detectedBrand);
                    if ((detectedBrand === 'xl' || detectedBrand === 'axis') && (fulltext.includes('xl') || fulltext.includes('axis'))) {
                        matchBrand = true;
                    }
                }

                // Match Durasi Paket
                let matchDuration = true;
                if (selectedDuration === 'harian') {
                    matchDuration = fulltext.includes('1 hari') || fulltext.includes('2 hari') || fulltext.includes('3 hari') || fulltext.includes('harian');
                } else if (selectedDuration === 'mingguan') {
                    matchDuration = fulltext.includes('7 hari') || fulltext.includes('minggu');
                } else if (selectedDuration === 'bulanan') {
                    matchDuration = fulltext.includes('30 hari') || fulltext.includes('bulan');
                }

                // Match Keyword Search
                let matchKeyword = !keyword || fulltext.includes(keyword);

                if (matchBrand && matchDuration && matchKeyword) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (visibleCount === 0) {
                notFoundState.classList.remove('hidden');
            } else {
                notFoundState.classList.add('hidden');
            }

            document.getElementById('filter_count').innerText = 'Menampilkan ' + visibleCount + ' produk';
        }

        function filterDuration(duration) {
            selectedDuration = duration;

            durationBtns.forEach(btn => {
                if (btn.getAttribute('data-duration') === duration) {
                    btn.classList.remove('bg-white', 'text-slate-700', 'border', 'border-slate-200');
                    btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                    btn.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-200');
                }
            });

            applyFilters();
        }

        phoneInput.addEventListener('input', function() {
            let phone = this.value.trim().replace(/[^0-9]/g, '');
            this.value = phone;

            if (phone.length >= 4) {
                let prefix = phone.substring(0, 4);
                detectedBrand = null;

                for (let provider in prefixMap) {
                    if (prefixMap[provider].includes(prefix)) {
                        detectedBrand = provider;
                        break;
                    }
                }

                if (detectedBrand) {
                    operatorBadge.classList.remove('hidden');
                    operatorName.innerText = detectedBrand.toUpperCase();
                } else {
                    operatorBadge.classList.add('hidden');
                }
            } else {
                detectedBrand = null;
                operatorBadge.classList.add('hidden');
            }

            applyFilters();
        });

        searchInput.addEventListener('input', applyFilters);

        function openCheckout(code, name, price) {
            let phone = phoneInput.value.trim();
            if (phone.length < 9) {
                alert('Silakan masukkan nomor HP tujuan yang valid terlebih dahulu!');
                phoneInput.focus();
                return;
            }

            document.getElementById('modal_product_code').value = code;
            document.getElementById('modal_product_name').innerText = name;
            document.getElementById('modal_product_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('modal_target_no').value = phone;
            document.getElementById('checkoutModal').classList.remove('hidden');
        }

        function closeCheckout() {
            document.getElementById('checkoutModal').classList.add('hidden');
        }
    </script>

</body>
</html>
