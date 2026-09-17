<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center max-w-md">
            <span class="font-bold text-lg">MOSANDY STORE</span>
            <a href="/" class="text-xs bg-white text-blue-600 px-3 py-1.5 rounded-lg font-bold">&larr; Batal</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-md">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 text-center">
            
            <!-- Countdown Timer -->
            <div class="bg-red-50 text-red-700 p-2.5 rounded-xl text-xs font-bold mb-4 flex justify-between items-center">
                <span>Batas Waktu Pembayaran:</span>
                <span id="qrisTimer" class="text-sm font-mono font-black text-red-600">15:00</span>
            </div>

            <p class="text-xs text-gray-500 font-bold uppercase">Kode Transaksi</p>
            <h3 class="text-base font-mono font-bold text-gray-800 mb-3">{{ $trx_id }}</h3>

            <div class="bg-gray-50 p-4 rounded-xl mb-4 text-left text-xs border border-gray-100">
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Produk:</span>
                    <span class="font-bold text-gray-800">{{ $product->name ?? 'Produk PPOB' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Nomor Tujuan:</span>
                    <span class="font-bold text-gray-800">{{ $target_no }}</span>
                </div>
                <div class="flex justify-between py-1 border-t border-gray-200 mt-1 pt-1">
                    <span class="text-gray-500">Total Bayar:</span>
                    <span class="font-black text-blue-600 text-sm">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tampilan QR Code -->
            <div class="my-4 flex justify-center">
                <div id="qrcode" class="p-3 bg-white border-2 border-dashed border-gray-300 rounded-2xl shadow-inner"></div>
            </div>

            <p class="text-xs text-gray-500 mb-4">Scan kode QRIS di atas menggunakan GoPay, OVO, Dana, ShopeePay, LinkAja, BCA, Mandiri, atau aplikasi m-Banking Anda.</p>

            <div class="space-y-2">
                <a href="/cek-pesanan?q={{ $trx_id }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow">
                    Cek Status Pembayaran
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20sudah%20bayar%20untuk%20TRX:%20{{ $trx_id }}%20Target:%20{{ $target_no }}" target="_blank" class="block w-full bg-green-50 text-green-700 hover:bg-green-100 font-bold py-2.5 rounded-xl text-xs border border-green-300 transition">
                    💬 Ada Kendala? Chat CS WhatsApp
                </a>
            </div>
        </div>
    </div>

    <script>
        // Generate Kode QRIS
        const qrisData = "{{ $qris_payload }}";
        const qr = qrcode(0, 'M');
        qr.addData(qrisData);
        qr.make();
        document.getElementById('qrcode').innerHTML = qr.createImgTag(5);

        // Timer 15 Menit
        let timeLeft = 15 * 60;
        const timerElem = document.getElementById('qrisTimer');

        const countdown = setInterval(() => {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            timerElem.innerText = `${minutes}:${seconds}`;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElem.innerText = "KADALUARSA";
                alert('Batas waktu pembayaran QRIS telah habis. Silakan lakukan order ulang.');
            }
            timeLeft--;
        }, 1000);
    </script>
</body>
</html>
