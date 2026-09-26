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
        <!-- Header Tagihan -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-3">
            <span class="text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 px-3 py-1 rounded-full">Instruksi Pembayaran</span>
            <h1 class="text-xl font-extrabold text-slate-900">Scan QRIS Untuk Bayar</h1>
            <p class="text-xs text-slate-500">Gunakan aplikasi DANA, OVO, GoPay, ShopeePay, LinkAja, atau M-Banking Anda.</p>
            
            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200">
                <p class="text-[11px] font-bold text-slate-500">ID Transaksi: <span class="text-slate-900 font-extrabold">{{ $trx_id }}</span></p>
                <p class="text-xs font-bold text-slate-500 mt-1">Total Tagihan:</p>
                <p class="text-2xl font-black text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tampilan QRIS Resmi GPN -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-4">
            <div class="bg-white rounded-2xl inline-block shadow-md overflow-hidden border border-slate-100">
                <img src="/images/qris.jpg" 
                     alt="QRIS Mosandy cell" 
                     class="w-full max-w-[280px] h-auto object-contain mx-auto">
            </div>
            
            <div class="pt-2">
                <a href="/cek-pesanan?q={{ $trx_id }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-md transition">
                    Konfirmasi Pembayaran &rarr;
                </a>
            </div>
        </div>
    </div>

</body>
</html>
