<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen p-4 space-y-4 shadow-sm text-center">
        <div class="border-b pb-3 text-left flex items-center justify-between">
            <a href="/" class="text-xs font-bold text-slate-500">&larr; Beranda</a>
            <span class="text-xs font-extrabold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">Menunggu Pembayaran</span>
        </div>

        <div class="space-y-1">
            <p class="text-xs font-bold text-slate-500">ID Transaksi: {{ $order->trx_id }}</p>
            <h2 class="text-sm font-extrabold text-slate-800">{{ $order->product_name }}</h2>
            <p class="text-2xl font-black text-blue-600">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
        </div>

        <!-- Tampilan QRIS Mosandy Cell -->
        <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-3">
            <p class="text-xs font-bold text-slate-700">Scan QRIS di bawah ini untuk membayar:</p>
            <div class="bg-white p-2 rounded-xl shadow-inner inline-block border">
                <!-- Menampilkan gambar QRIS Mosandy Cell -->
                <img src="/assets/images/qris-mosandy.jpg" alt="QRIS Mosandy Cell" class="w-64 mx-auto rounded-lg" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MosandyCell-{{ $order->trx_id }}'">
            </div>
            <div class="text-[11px] text-slate-500 font-medium">
                <p class="font-extrabold text-slate-800">Mosandy Cell</p>
                <p>NMID: ID1026586951067</p>
                <p class="text-[10px] text-slate-400 mt-1">Dapat di-scan menggunakan DANA, GoPay, OVO, ShopeePay, BCA, Mandiri, BRI, & aplikasi e-wallet / m-banking lainnya.</p>
            </div>
        </div>

        <div class="text-left bg-blue-50 p-3 rounded-xl space-y-1 text-xs">
            <p class="font-bold text-blue-900">Cara Pembayaran:</p>
            <ol class="list-decimal list-inside text-blue-800 space-y-0.5 text-[11px]">
                <li>Simpan / Screenshot gambar QRIS di atas.</li>
                <li>Buka aplikasi m-Banking atau E-Wallet pilihan Anda.</li>
                <li>Pilih menu <b>Scan / QRIS</b> lalu unggah screenshot QRIS.</li>
                <li>Masukkan nominal presisi: <b>Rp {{ number_format($order->price, 0, ',', '.') }}</b>.</li>
                <li>Selesaikan pembayaran.</li>
            </ol>
        </div>

        <a href="/cek-pesanan?trx_id={{ $order->trx_id }}" class="block w-full bg-blue-600 text-white font-extrabold py-3 rounded-xl shadow-md text-sm">
            Cek Status Transaksi
        </a>
    </div>

</body>
</html>
