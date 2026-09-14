<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 text-white p-4 text-center font-bold text-lg shadow-md">
        MOSANDY STORE - Pembayaran QRIS
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-md">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">MENUNGGU PEMBAYARAN</span>
            
            <h3 class="font-bold text-gray-800 mt-4 text-lg">{{ $product->name ?? 'Produk PPOB' }}</h3>
            <p class="text-xs text-gray-500 mt-1">No. Tujuan / ID: <span class="font-bold text-gray-800">{{ $target_no }}</span></p>
            <p class="text-[10px] text-gray-400">Kode Transaksi: {{ $trx_id }}</p>
            
            <div class="my-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-xs text-gray-500">Total Pembayaran</p>
                <p class="text-2xl font-extrabold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>

            <!-- QRIS Code Image Generator -->
            <div class="flex flex-col items-center justify-center my-4 p-4 bg-white border-2 border-dashed border-gray-300 rounded-2xl">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qris_payload) }}" alt="QRIS Code" class="w-56 h-56 object-contain rounded-lg shadow-sm">
                <span class="mt-2 text-[11px] font-bold text-gray-600 tracking-wider">QRIS NATIONAL STANDARD</span>
            </div>

            <p class="text-xs text-gray-500 mb-4">Scan kode QRIS di atas menggunakan GoPay, OVO, Dana, ShopeePay, LinkAja, atau aplikasi Mobile Banking apapun.</p>

            <a href="/" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow">Selesai / Kembali</a>
        </div>
    </div>
</body>
</html>
