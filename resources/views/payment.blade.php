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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative p-5 space-y-6 pb-12">
        
        <!-- Header Nav -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <a href="/" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold">&larr;</a>
            <h1 class="text-base font-extrabold text-slate-900">Selesaikan Pembayaran</h1>
            <div class="w-9"></div>
        </div>

        <!-- Tagihan Total -->
        <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-3xl p-5 text-white text-center space-y-1 shadow-lg shadow-blue-500/20">
            <p class="text-[11px] font-extrabold text-blue-200 uppercase tracking-widest">Total Tagihan Pembayaran</p>
            <p class="text-2xl font-black tracking-tight">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
            <p class="text-[10px] text-blue-200 pt-1">Kode Transaksi: <span class="font-mono bg-white/20 px-2 py-0.5 rounded-md font-bold select-all">{{ $order->trx_id }}</span></p>
        </div>

        <!-- Box Tampilan Gambar QRIS Asli Mosandy Cell -->
        <div class="border border-slate-200 rounded-3xl p-4 bg-slate-50 text-center space-y-3 shadow-sm">
            
            <!-- Gambar QRIS Asli Tanpa Diubah -->
            <div class="bg-white p-2 rounded-2xl border border-slate-200 inline-block shadow-md w-full overflow-hidden">
                <img src="/images/qris.jpg" 
                     alt="QRIS Mosandy Cell Asli" 
                     class="w-full h-auto rounded-xl object-contain mx-auto"
                     onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=350x350&margin=10&data=00020101021126580014ID.GO.IDC.WWW01189360091500000000000215ID10265869510670303A015104520453033602ID5912Mosandy%20cell6013KOTA%20JAKARTA61051234563048821';">
            </div>

            <div class="space-y-1">
                <p class="text-[11px] font-extrabold text-slate-800">Bisa di-scan dengan semua aplikasi:</p>
                <p class="text-[10px] font-semibold text-slate-500">DANA, OVO, GoPay, ShopeePay, LinkAja, BCA, Mandiri, BRI, BNI, dll.</p>
            </div>
        </div>

        <!-- Tombol Konfirmasi WA Admin -->
        <div class="space-y-3">
            <a href="{{ $waUrl }}" target="_blank" 
               class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-4 rounded-2xl text-xs shadow-lg shadow-emerald-600/30 active:scale-95 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                <span>Konfirmasi Pembayaran via WA (08777480215)</span>
            </a>

            <a href="/cek-pesanan?trx_id={{ $order->trx_id }}" 
               class="w-full bg-slate-100 text-slate-700 font-extrabold py-3.5 rounded-2xl text-xs text-center block">
                Cek Status Pesanan
            </a>
        </div>

    </div>

</body>
</html>
