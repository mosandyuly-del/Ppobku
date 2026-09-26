<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between p-4">

    <div class="max-w-md mx-auto w-full space-y-4 my-auto">
        <!-- Header Pembayaran -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-3">
            <span class="text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 px-3 py-1 rounded-full">Instruksi Pembayaran</span>
            <h1 class="text-xl font-extrabold text-slate-900">Scan QRIS Untuk Bayar</h1>
            <p class="text-xs text-slate-500">Silakan scan kode QRIS di bawah ini menggunakan DANA, OVO, GoPay, ShopeePay, LinkAja, atau Mobile Banking.</p>
            
            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200">
                <p class="text-[11px] font-bold text-slate-500">ID Transaksi: <span class="text-slate-900 font-extrabold">{{ $trx_id }}</span></p>
                <p class="text-xs font-bold text-slate-500 mt-1">Total Tagihan:</p>
                <p class="text-2xl font-black text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tampilan QRIS Resmi Mosandy cell -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-4">
            <div class="p-3 bg-white border-2 border-slate-900 rounded-2xl inline-block shadow-md">
                <img src="/images/qris.jpg" 
                     alt="QRIS Mosandy cell" 
                     class="w-72 h-auto object-contain mx-auto rounded-lg">
            </div>
            
            <div class="text-xs font-bold text-slate-700 space-y-1">
                <p class="text-base font-black text-slate-900">Mosandy cell</p>
                <p class="text-[11px] text-slate-500">NMID: ID1026586951067</p>
                <p class="text-[10px] text-emerald-600 font-extrabold uppercase bg-emerald-50 py-1 px-3 rounded-full inline-block mt-1">
                    ✓ DANA Bisnis • QRIS Nasional
                </p>
            </div>

            <div class="pt-2">
                <a href="/cek-pesanan?q={{ $trx_id }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-md transition">
                    Cek Status Pembayaran &rarr;
                </a>
            </div>
        </div>
    </div>

</body>
</html>
