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
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold tracking-wide">MOSANDY STORE</a>
            <a href="/" class="text-sm bg-white text-blue-600 px-3 py-1.5 rounded-lg font-semibold">&larr; Kembali</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Layanan {{ $title }}</h2>

        @if(count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $item)
                    @php
                        $nama = $item->name ?? $item->nama ?? 'Produk PPOB';
                        $harga = $item->price ?? $item->harga ?? 0;
                        $sub = $item->brand ?? $item->category ?? 'Digiflazz';
                        $code = $item->code ?? $item->sku ?? $item->id;
                    @endphp
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center hover:shadow-md transition">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $nama }}</h3>
                            <p class="text-xs text-gray-500">{{ $sub }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-blue-600 font-bold text-sm block">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                            <button onclick="openBuyModal('{{ $code }}', '{{ addslashes($nama) }}', '{{ $harga }}')" class="mt-1 text-xs bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-1.5 rounded-lg shadow">
                                Beli
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-6 rounded-xl text-center text-gray-500 shadow-sm">
                <p class="font-semibold">Produk {{ $title }} Belum Tersedia</p>
                <p class="text-xs mt-1 text-gray-400">Proses sinkronisasi data dari Digiflazz sedang berjalan di latar belakang.</p>
            </div>
        @endif
    </div>

    <!-- Modal Form Pembelian -->
    <div id="buyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="closeBuyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            
            <h3 class="text-lg font-bold text-gray-800 mb-1" id="modalProductName">Detail Pembelian</h3>
            <p class="text-xs text-gray-500 mb-4">Total: <span id="modalProductPrice" class="font-bold text-blue-600 text-sm"></span></p>

            <form action="/checkout" method="POST">
                @csrf
                <input type="hidden" name="product_code" id="modalProductCode">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Tujuan / ID Pelanggan / ID Game</label>
                    <input type="text" name="target_no" required placeholder="Contoh: 081234567890 atau ID Game" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Metode Pembayaran</label>
                    <div class="p-3 border rounded-lg bg-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-800">QRIS (Otomatis)</span>
                        <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">Bisa All E-Wallet / M-Banking</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow text-sm transition">
                    Lanjut Pembayaran QRIS &rarr;
                </button>
            </form>
        </div>
    </div>

    <script>
        function openBuyModal(code, name, price) {
            document.getElementById('modalProductCode').value = code;
            document.getElementById('modalProductName').innerText = name;
            document.getElementById('modalProductPrice').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('buyModal').classList.remove('hidden');
        }

        function closeBuyModal() {
            document.getElementById('buyModal').classList.add('hidden');
        }
    </script>
</body>
</html>
