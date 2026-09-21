<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Katalog Produk' }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
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

    <main class="max-w-4xl mx-auto w-full px-4 py-6 space-y-6">

        @if(session('error'))
            <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Banner Judul Kategori -->
        <div class="bg-blue-600 p-6 rounded-3xl text-white shadow-lg shadow-blue-500/20">
            <span class="text-[10px] font-black uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full">Katalog Digital</span>
            <h2 class="text-2xl font-black mt-2">{{ $title }}</h2>
            <p class="text-xs text-blue-100 mt-1">Masukkan nomor HP tujuan terlebih dahulu untuk mendeteksi operator otomatis.</p>
        </div>

        <!-- Input Nomor HP Utama untuk Auto Detection -->
        <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 space-y-3">
            <label class="block text-xs font-extrabold text-slate-700 uppercase">Masukkan Nomor HP Tujuan</label>
            <div class="relative">
                <input type="tel" id="input_phone" placeholder="Contoh: 081234567890" autofocus
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-extrabold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <div id="operator_badge" class="absolute right-3 top-3 hidden">
                    <span id="operator_name" class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-blue-100 text-blue-700">TELKOMSEL</span>
                </div>
            </div>
            <p id="phone_hint" class="text-[11px] font-semibold text-slate-400">Ketik minimal 4 digit nomor HP (misal: 0812, 0857, 0818, 0896) untuk melihat pilihan produk.</p>
        </div>

        <!-- Container Tempat Produk Muncul Setelah Nomor Diisi -->
        <div id="product_container" class="space-y-4">
            
            <!-- State Kosong (Sebelum Nomor Diisi) -->
            <div id="empty_state" class="bg-white p-8 rounded-3xl border border-dashed border-slate-300 text-center space-y-2">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto text-blue-600 font-bold text-xl">📱</div>
                <h3 class="font-extrabold text-slate-800 text-sm">Nomor HP Belum Diisi</h3>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Silakan ketik nomor ponsel kamu di kolom atas. Produk akan otomatis ditampilkan sesuai operator nomor kamu.</p>
            </div>

            <!-- Daftar Produk (Hidden Secara Default) -->
            <div id="product_grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden">
                @foreach($products as $p)
                    @php
                        $brandLower = strtolower($p->brand ?? '');
                        $nameLower = strtolower($p->name ?? '');
                        $fullText = $brandLower . ' ' . $nameLower;
                    @endphp
                    <div class="product-card bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-blue-300 transition"
                        data-brand="{{ $brandLower }}"
                        data-fulltext="{{ $fullText }}">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md uppercase">{{ $p->brand ?? 'PPOB' }}</span>
                                <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md">Instan 1-3s</span>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $p->name }}</h3>
                            <p class="text-xs font-black text-blue-600 mt-2">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                        </div>

                        <button onclick="openCheckout('{{ $p->code ?? $p->sku }}', '{{ addslashes($p->name) }}', '{{ $p->price }}')" 
                            class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold py-2.5 rounded-xl shadow-md transition text-center">
                            Beli Sekarang &rarr;
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- State Produk Tidak Ditemukan -->
            <div id="no_match_state" class="bg-white p-8 rounded-3xl border border-slate-200 text-center hidden">
                <p class="text-xs font-bold text-slate-500">Tidak ada produk yang cocok untuk provider nomor tersebut.</p>
            </div>

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
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase mb-1">Nomor Tujuan / ID Pelanggan</label>
                    <input type="text" name="target_no" id="modal_target_no" required readonly
                        class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase mb-1">Metode Pembayaran</label>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs font-bold text-slate-800">
                        <span>QRIS & Multi Payment 24 Jam</span>
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2 py-0.5 rounded">OTOMATIS</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-lg shadow-blue-500/25 transition">
                    Lanjut Pembayaran &rarr;
                </button>
            </form>

        </div>
    </div>

    <!-- Script Auto Detection Provider -->
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
        const emptyState = document.getElementById('empty_state');
        const productGrid = document.getElementById('product_grid');
        const noMatchState = document.getElementById('no_match_state');
        const operatorBadge = document.getElementById('operator_badge');
        const operatorName = document.getElementById('operator_name');
        const productCards = document.querySelectorAll('.product-card');

        phoneInput.addEventListener('input', function() {
            let phone = this.value.trim().replace(/[^0-9]/g, '');
            this.value = phone;

            if (phone.length < 4) {
                emptyState.classList.remove('hidden');
                productGrid.classList.add('hidden');
                noMatchState.classList.add('hidden');
                operatorBadge.classList.add('hidden');
                return;
            }

            let prefix = phone.substring(0, 4);
            let detectedProvider = null;

            for (let provider in prefixMap) {
                if (prefixMap[provider].includes(prefix)) {
                    detectedProvider = provider;
                    break;
                }
            }

            emptyState.classList.add('hidden');

            if (detectedProvider) {
                operatorBadge.classList.remove('hidden');
                operatorName.innerText = detectedProvider.toUpperCase();

                let visibleCount = 0;
                productCards.forEach(card => {
                    let fulltext = card.getAttribute('data-fulltext');
                    // Jika provider xl atau axis, tampilkan produk xl & axis
                    let isMatch = fulltext.includes(detectedProvider);
                    if ((detectedProvider === 'xl' || detectedProvider === 'axis') && (fulltext.includes('xl') || fulltext.includes('axis'))) {
                        isMatch = true;
                    }

                    if (isMatch) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (visibleCount > 0) {
                    productGrid.classList.remove('hidden');
                    noMatchState.classList.add('hidden');
                } else {
                    // Fallback jika nama brand tidak persis, tampilkan semua produk
                    productCards.forEach(card => card.classList.remove('hidden'));
                    productGrid.classList.remove('hidden');
                    noMatchState.classList.add('hidden');
                }

            } else {
                operatorBadge.classList.remove('hidden');
                operatorName.innerText = 'UMUM / LAINNYA';
                
                // Tampilkan semua produk jika prefix tidak dikenali
                productCards.forEach(card => card.classList.remove('hidden'));
                productGrid.classList.remove('hidden');
                noMatchState.classList.add('hidden');
            }
        });

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

    <footer class="text-center text-[10px] text-slate-400 py-4">
        &copy; {{ date('Y') }} MOSANDY STORE • All Rights Reserved.
    </footer>

</body>
</html>
