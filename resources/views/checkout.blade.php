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
        <!-- Detail Tagihan -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-3">
            <span class="text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 px-3 py-1 rounded-full">Rincian Pesanan</span>
            <h1 class="text-xl font-extrabold text-slate-900">Scan QRIS Untuk Bayar</h1>
            <p class="text-xs text-slate-500">Gunakan DANA, OVO, GoPay, ShopeePay, LinkAja, atau Mobile Banking.</p>
            
            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200">
                <p class="text-[11px] font-bold text-slate-500">ID Transaksi: <span class="text-slate-900 font-extrabold">{{ $trx_id }}</span></p>
                <p class="text-xs font-bold text-slate-500 mt-1">Total Tagihan:</p>
                <p class="text-2xl font-black text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- QRIS Card GPN Presisi -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center space-y-4">
            <div class="border-2 border-slate-200 rounded-2xl p-4 bg-white shadow-inner max-w-[300px] mx-auto space-y-3">
                <div class="flex items-center justify-between border-b pb-2">
                    <span class="text-xs font-black text-red-600 tracking-wider">QRIS</span>
                    <span class="text-[10px] font-bold text-slate-400">GPN Nasional</span>
                </div>
                
                <div class="text-center">
                    <h2 class="text-base font-black text-slate-900">Mosandy cell</h2>
                    <p class="text-[10px] font-bold text-slate-500">NMID: ID1026586951067</p>
                </div>

                <!-- QR Generator Langsung dari Payload DANA Mosandy cell -->
                <div class="p-2 bg-white rounded-xl border border-slate-100 flex justify-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=00020101021126580014ID.LINKAJA.WWW01189360091100223000000215MOSANDY%20CELL5204581253033605802ID5912MOSANDY%20CELL6007JEMBER6304C1C7" 
                         alt="QRIS Mosandy Cell" 
                         class="w-56 h-56 object-contain">
                </div>

                <p class="text-[10px] font-extrabold text-slate-600 tracking-wide uppercase">Satu QRIS Untuk Semua</p>
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
