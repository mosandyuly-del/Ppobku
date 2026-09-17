<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan {{ $title }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        }
        .product-card-hover {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(37, 99, 235, 0.15);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="gradient-bg text-white text-[11px] font-semibold py-2 px-4 shadow-inner">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2 truncate">
                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider animate-pulse">LIVE</span>
                <span class="opacity-90">Sistem Otomatis 24 Jam Nonstop • QRIS All Payment • Proses Instan 1-3 Detik</span>
            </div>
            <a href="/cek-pesanan" class="hidden sm:inline-flex items-center space-x-1 text-blue-200 hover:text-white transition font-bold">
                <span>Lacak Pesanan</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- Header Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200/80 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3.5 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-9 h-9 gradient-bg rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md shadow-blue-500/20">
                    M
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">MOSANDY <span class="text-blue-600">STORE</span></h1>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">PPOB & Digital Marketplace</span>
                </div>
            </a>
            <div class="flex items-center space-x-2">
                <a href="/cek-pesanan" class="sm:hidden text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl font-bold transition">Lacak</a>
                <a href="/" class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-2 rounded-xl font-bold transition flex items-center space-x-1">
                    <span>&larr;</span>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- Hero Title Banner -->
        <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/10 relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-block bg-white/20 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">Katalog Layanan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $title }}</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl font-medium">Pilih produk yang kamu butuhkan, masukkan nomor tujuan, dan selesaikan pembayaran secara serba otomatis.</p>
            </div>
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        @php
            $slug = strtolower($title);$isMobileCategory = stristr($slug, 'PULSA') \vert{}\vert{} stristr($slug, 'DATA');
            $isDataCategory = stristr($slug, 'DATA');
            $isGameCategory = stristr($slug, 'GAME');

            $gameLogos = [
                'mobile legends' => 'https://img.utdstc.com/icon/4be/3f5/4be3f5c9e2b0efbd6abf722ff41adfb3050c5d26392305374e5bd8cb4948a58a:200',
                'free fire' => 'https://img.utdstc.com/icon/4e7/b99/4e7b99c0ec9e1d713c2f902a24f0c45969ceeeaaef48950d885a03e1e67e3ad6:200',
                'pubg' => 'https://img.utdstc.com/icon/4ad/7bd/4ad7bd0dd733d3bd2bb08a1c62fdfbf9b68e9185a53fbcae212fbd65b69f6e5a:200',
                'genshin' => 'https://img.utdstc.com/icon/6b6/4f3/6b64f3d2f2dfed05e608aa86be342bf23e9ca29f4ff89aef3bf67b8d4fbf2f86:200',
                'valorant' => 'https://img.utdstc.com/icon/8d9/d9f/8d9d9f5787c95e1c450bf00e57f5c5314ecf1db320d3f8bc9fb1d2fb737df98f:200'
            ];

            $uniqueGames = collect();
            if ($isGameCategory && count($products) > 0) {
                $uniqueGames =$products->pluck('brand')->unique()->filter()->values();
            }
        @endphp

        <!-- Form Input Nomor HP dengan Auto-Detect -->
        @if($isMobileCategory)
            <div class="glass-card p-6 rounded-3xl shadow-sm mb-8 border border-slate-200/80">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">1. Masukkan Nomor Handphone</label>
                    <button type="button" onclick="pastePhoneNumber()" class="text-xs text-blue-600 hover:text-blue-700 font-bold flex items-center space-x-1 bg-blue-50 px-2.5 py-1 rounded-lg transition">
                        <span>📋</span>
                        <span>Tempel Nomor</span>
                    </button>
                </div>
                <div class="relative">
                    <input type="tel" id="phoneNumber" placeholder="Contoh: 081234567890" autocomplete="off"
                        class="w-full pl-4 pr-32 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl text-base font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition"
                        oninput="filterProducts()">
                    <div id="operatorBadge" class="absolute right-3 top-3 hidden px-3 py-1 bg-blue-600 text-white text-xs font-black rounded-xl uppercase tracking-wider shadow-sm">
                        -
                    </div>
                </div>
                <p id="operatorInfo" class="text-xs text-slate-400 font-medium mt-2">Operator akan terdeteksi otomatis begitu 4 digit pertama diketik.</p>
                
                @if($isDataCategory)
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-3">2. Pilih Masa Aktif Paket</label>
                        <div class="flex flex-wrap gap-2" id="durationFilters">
                            <button type="button" onclick="setDuration('all')" class="duration-btn bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-blue-500/20 transition" data-duration="all">
                                Semua Masa Aktif
                            </button>
                            <button type="button" onclick="setDuration('harian')" class="duration-btn bg-white text-slate-600 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl hover:bg-slate-50 transition" data-duration="harian">
                                Harian (1-6 Hari)
                            </button>
                            <button type="button" onclick="setDuration('mingguan')" class="duration-btn bg-white text-slate-600 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl hover:bg-slate-50 transition" data-duration="mingguan">
                                Mingguan (7 Hari)
                            </button>
                            <button type="button" onclick="setDuration('dwimingguan')" class="duration-btn bg-white text-slate-600 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl hover:bg-slate-50 transition" data-duration="dwimingguan">
                                Dwi Mingguan (14-15 Hari)
                            </button>
                            <button type="button" onclick="setDuration('bulanan')" class="duration-btn bg-white text-slate-600 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl hover:bg-slate-50 transition" data-duration="bulanan">
                                Bulanan (28-30 Hari)
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Filter Logo Game -->
        @if($isGameCategory)
            <div class="glass-card p-6 rounded-3xl shadow-sm mb-8 border border-slate-200/80">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-4">Pilih Game Digiflazz</label>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3" id="gameFilters">
                    <button type="button" onclick="setGame('all')" class="game-btn bg-blue-600 text-white p-3 rounded-2xl flex flex-col items-center justify-center text-center transition shadow-lg shadow-blue-500/20" data-game="all">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-xs mb-1">ALL</div>
                        <span class="text-xs font-bold">Semua Game</span>
                    </button>

                    @foreach($uniqueGames as$g)
                        @php
                            $lowerG = strtolower($g);$logoUrl = 'https://cdn-icons-png.flaticon.com/512/686/686589.png';
                            foreach($gameLogos as $key =>$url) {
                                if(stristr($lowerG,$key)) {
                                    $logoUrl =$url;
                                    break;
                                }
                            }
                        @endphp
                        <button type="button" onclick="setGame('{{ addslashes($g) }}')" class="game-btn bg-white border border-slate-200 p-3 rounded-2xl flex flex-col items-center justify-center text-center hover:border-blue-500 hover:shadow-md transition" data-game="{{ $g }}">
                            <img src="{{ $logoUrl }}" alt="{{ $g }}" class="w-10 h-10 object-cover rounded-xl mb-1.5 shadow-sm">
                            <span class="text-[11px] font-bold text-slate-700 truncate w-full">{{ $g }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Grid Produk -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($products as$item)
                @php
                    $nama = $item->name ?? $item->nama ?? 'Produk PPOB';
                    $harga = $item->price ?? $item->harga ?? 0;
                    $brand = strtolower($item->brand ?? $item->category ?? 'umum');
                    $code =$item->code ?? $item->sku ?? $item->id;
                @endphp
                <div class="product-card product-card-hover bg-white p-5 rounded-2xl border border-slate-200/80 flex flex-col justify-between shadow-sm"
                     data-brand="{{ $brand }}"
                     data-name="{{ strtolower($nama) }}">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] bg-slate-100 text-slate-600 font-extrabold px-2.5 py-1 rounded-lg uppercase tracking-wider">{{ $item->brand ?? $item->category }}</span>
                            <span class="text-[9px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded-md flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Instan 1-3s</span>
                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug mb-4">{{ $nama }}</h3>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 font-semibold block">Harga Produk</span>
                            <span class="text-blue-600 font-extrabold text-base">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                        </div>
                        <button onclick="selectProduct('{{ $code }}', '{{ addslashes($nama) }}', '{{$harga }}')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-blue-500/20 transition active:scale-95">
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 rounded-3xl text-center border border-slate-200/80 shadow-sm">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-2xl">!</div>
                    <h3 class="font-bold text-slate-800 text-base">Produk Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-400 mt-1">Belum ada pilihan produk aktif di kategori ini.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Modal Purchase -->
    <div id="buyModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative border border-slate-100 transform transition-all">
            <button onclick="closeBuyModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1 rounded-full bg-slate-100 transition">&times;</button>
            
            <div class="mb-4">
                <span class="text-[10px] bg-blue-50 text-blue-600 font-extrabold px-2.5 py-1 rounded-lg uppercase tracking-wider">Konfirmasi Pembelian</span>
                <h3 class="text-lg font-extrabold text-slate-900 mt-1" id="modalProductName">Detail Pembelian</h3>
                <p class="text-xs text-slate-500">Total Tagihan: <span id="modalProductPrice" class="font-black text-blue-600 text-sm"></span></p>
            </div>

            <form action="/checkout" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_code" id="modalProductCode">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Nomor Tujuan / ID Pelanggan</label>
                    <input type="text" name="target_no" id="modalTargetNo" required placeholder="Contoh: 081234567890 / ID Game" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Metode Pembayaran</label>
                    <div class="p-3.5 border border-blue-200 rounded-2xl bg-blue-50/50 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                            <span class="text-xs font-extrabold text-slate-800">QRIS (Otomatis 24 Jam)</span>
                        </div>
                        <span class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded-full font-bold uppercase">All E-Wallet</span>
                    </div>
                </div>

                <button type="submit" class="w-full gradient-bg hover:opacity-95 text-white font-extrabold py-3.5 rounded-xl shadow-lg shadow-blue-500/25 text-xs transition active:scale-98">
                    Bayar Sekarang Via QRIS &rarr;
                </button>
            </form>
        </div>
    </div>

    <script>
        let selectedDuration = 'all';
        let selectedGame = 'all';

        const operatorPrefixes = {
            'telkomsel': ['0811','0812','0813','0821','0822','0823','0851','0852','0853'],
            'indosat': ['0814','0815','0816','0855','0856','0857','0858'],
            'xl': ['0817','0818','0819','0859','0877','0878'],
            'axis': ['0831','0832','0833','0838'],
            'tri': ['0895','0896','0897','0898','0899'],
            'smartfren': ['0881','0882','0883','0884','0885','0886','0887','0888','0889']
        };

        async function pastePhoneNumber() {
            try {
                const text = await navigator.clipboard.readText();
                const phoneInput = document.getElementById('phoneNumber');
                if (phoneInput && text) {
                    phoneInput.value = text.trim();
                    filterProducts();
                }
            } catch (err) {
                alert('Gagal membaca papan klip. Pastikan izin paste diizinkan browser.');
            }
        }

        function setGame(gameName) {
            selectedGame = gameName.toLowerCase();
            document.querySelectorAll('.game-btn').forEach(btn => {
                if (btn.getAttribute('data-game').toLowerCase() === selectedGame) {
                    btn.className = 'game-btn bg-blue-600 text-white p-3 rounded-2xl flex flex-col items-center justify-center text-center transition shadow-lg shadow-blue-500/20';
                } else {
                    btn.className = 'game-btn bg-white border border-slate-200 p-3 rounded-2xl flex flex-col items-center justify-center text-center hover:border-blue-500 hover:shadow-md transition';
                }
            });
            filterProducts();
        }

        function setDuration(duration) {
            selectedDuration = duration;
            document.querySelectorAll('.duration-btn').forEach(btn => {
                if (btn.getAttribute('data-duration') === duration) {
                    btn.className = 'duration-btn bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-blue-500/20 transition';
                } else {
                    btn.className = 'duration-btn bg-white text-slate-600 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl hover:bg-slate-50 transition';
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
                info.innerText = 'Operator akan terdeteksi otomatis begitu 4 digit pertama diketik.';
            }

            cards.forEach(card => {
                const brand = card.getAttribute('data-brand');
                const name = card.getAttribute('data-name');

                let matchOperator = true;
                if (detectedProvider) {
                    matchOperator = brand.includes(detectedProvider) || name.includes(detectedProvider);
                }

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

                let matchGame = true;
                if (selectedGame !== 'all') {
                    matchGame = brand.includes(selectedGame) || name.includes(selectedGame);
                }

                if (matchOperator && matchDuration && matchGame) {
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
