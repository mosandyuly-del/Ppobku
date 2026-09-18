<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan {{ $title }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Top Announcement Bar -->
    <div class="bg-blue-900 text-white text-[11px] font-semibold py-2 px-4 shadow-inner">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2 truncate">
                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">LIVE</span>
                <span class="opacity-90">Sistem Otomatis 24 Jam Nonstop • QRIS All Payment • Proses Instan 1-3 Detik</span>
            </div>
            <a href="/cek-pesanan" class="hidden sm:inline-flex items-center space-x-1 text-blue-200 hover:text-white transition font-bold">
                <span>Lacak Pesanan &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Header Navbar -->
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3.5 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md">
                    M
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">MOSANDY <span class="text-blue-600">STORE</span></h1>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">PPOB & Digital Marketplace</span>
                </div>
            </a>
            <div class="flex items-center space-x-2">
                <a href="/cek-pesanan" class="sm:hidden text-xs bg-slate-100 text-slate-700 px-3 py-2 rounded-xl font-bold">Lacak</a>
                <a href="/" class="text-xs bg-blue-50 text-blue-600 px-4 py-2 rounded-xl font-bold">&larr; Kembali</a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- Hero Title Banner -->
        <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl">
            <span class="inline-block bg-white/20 text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase mb-2">Katalog Layanan</span>
            <h2 class="text-2xl font-extrabold tracking-tight">{{ $title }}</h2>
            <p class="text-blue-100 text-xs mt-1">Pilih produk yang kamu butuhkan, masukkan nomor tujuan, dan selesaikan pembayaran via QRIS.</p>
        </div>

        <!-- Form Input Nomor HP -->
        <div class="bg-white p-6 rounded-3xl shadow-sm mb-8 border border-slate-200">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-xs font-extrabold text-slate-700 uppercase">1. Masukkan Nomor Tujuan / ID Pelanggan</label>
                <button type="button" onclick="pastePhoneNumber()" class="text-xs text-blue-600 font-bold bg-blue-50 px-2.5 py-1 rounded-lg">📋 Tempel</button>
            </div>
            <div class="relative">
                <input type="tel" id="phoneNumber" placeholder="Contoh: 081234567890 / ID Game" autocomplete="off"
                    class="w-full pl-4 pr-28 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    oninput="filterProducts()">
                <div id="operatorBadge" class="absolute right-3 top-3 hidden px-3 py-1 bg-blue-600 text-white text-xs font-black rounded-xl uppercase">
                    -
                </div>
            </div>
            <p id="operatorInfo" class="text-xs text-slate-400 font-medium mt-2">Operator akan terdeteksi otomatis saat 4 digit pertama diketik.</p>
        </div>

        <!-- Grid Produk (Diolah via PHP standar) -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if(isset($products) && count($products) > 0): ?>
                <?php foreach($products as$item): ?>
                    <?php
                        $nama =$item->name ?? 'Produk PPOB';
                        $harga =$item->price ?? 0;
                        $brand = strtolower($item->brand ?? $item->category ?? 'umum');
                        $code =$item->code ?? $item->sku ?? $item->id;
                    ?>
                    <div class="product-card bg-white p-5 rounded-2xl border border-slate-200 flex flex-col justify-between shadow-sm"
                         data-brand="<?php echo $brand; ?>"
                         data-name="<?php echo strtolower($nama); ?>">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] bg-slate-100 text-slate-600 font-extrabold px-2.5 py-1 rounded-lg uppercase"><?php echo $item->brand ?? $item->category; ?></span>
                                <span class="text-[9px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded-md">Instan 1-3s</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm leading-snug mb-4"><?php echo $nama; ?></h3>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Harga Produk</span>
                                <span class="text-blue-600 font-extrabold text-base">Rp <?php echo number_format($harga, 0, ',', '.'); ?></span>
                            </div>
                            <button onclick="selectProduct('<?php echo $code; ?>', '<?php echo addslashes($nama); ?>', '<?php echo$harga; ?>')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition">
                                Beli
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full bg-white p-12 rounded-3xl text-center border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 text-base">Produk Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-400 mt-1">Belum ada pilihan produk di kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Purchase -->
    <div id="buyModal" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="closeBuyModal()" class="absolute top-5 right-5 text-slate-400 p-1 rounded-full bg-slate-100 text-lg font-bold">&times;</button>
            
            <div class="mb-4">
                <span class="text-[10px] bg-blue-50 text-blue-600 font-extrabold px-2.5 py-1 rounded-lg uppercase">Konfirmasi Pembelian</span>
                <h3 class="text-lg font-extrabold text-slate-900 mt-1" id="modalProductName">Detail Pembelian</h3>
                <p class="text-xs text-slate-500">Total Tagihan: <span id="modalProductPrice" class="font-black text-blue-600 text-sm"></span></p>
            </div>

            <form action="/checkout" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_code" id="modalProductCode">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Nomor Tujuan / ID Pelanggan</label>
                    <input type="text" name="target_no" id="modalTargetNo" required placeholder="Contoh: 081234567890 / ID Game" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Metode Pembayaran</label>
                    <div class="p-3.5 border border-blue-200 rounded-2xl bg-blue-50 flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-800">QRIS (Otomatis 24 Jam)</span>
                        <span class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded-full font-bold uppercase">All E-Wallet</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs transition">
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

        async function pastePhoneNumber() {
            try {
                const text = await navigator.clipboard.readText();
                const phoneInput = document.getElementById('phoneNumber');
                if (phoneInput && text) {
                    phoneInput.value = text.trim();
                    filterProducts();
                }
            } catch (err) {
                alert('Gagal membaca papan klip.');
            }
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
                info.innerText = 'Operator akan terdeteksi otomatis saat 4 digit pertama diketik.';
            }

            cards.forEach(card => {
                const brand = card.getAttribute('data-brand');
                const name = card.getAttribute('data-name');

                let matchOperator = true;
                if (detectedProvider) {
                    matchOperator = brand.includes(detectedProvider) || name.includes(detectedProvider);
                }

                if (matchOperator) {
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
