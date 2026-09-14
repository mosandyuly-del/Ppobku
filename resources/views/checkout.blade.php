<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 text-white p-4 text-center font-bold text-lg shadow-md">
        MOSANDY STORE - Pembayaran QRIS
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-md">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">MENUNGGU PEMBAYARAN</span>
            
            <h3 class="font-bold text-gray-800 mt-4">{{ $product->name ?? 'Produk PPOB' }}</h3>
            <p class="text-sm text-gray-500">No. Tujuan / ID: <span class="font-semibold text-gray-800">{{ $target_no }}</span></p>
            
            <div class="my-4 p-4 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500">Total Pembayaran</p>
                <p class="text-2xl font-extrabold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>

            <!-- Display QRIS Code -->
            <div class="flex justify-center my-4">
                <div id="qrcode" class="p-2 border-2 border-gray-300 rounded-xl bg-white"></div>
            </div>

            <p class="text-xs text-gray-400 mb-4">Scan menggunakan GoPay, OVO, Dana, ShopeePay, atau Mobile Banking apa saja.</p>

            <a href="/" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">Selesai / Kembali</a>
        </div>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $qris_data }}",
            width: 200,
            height: 200
        });
    </script>
</body>
</html>
