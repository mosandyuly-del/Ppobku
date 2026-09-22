<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-200">
        
        <!-- Header Pembayaran -->
        <div class="text-center mb-6">
            <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">Pembayaran Manual DANA</span>
            <h2 class="text-xl font-extrabold text-slate-900">Selesaikan Pembayaran</h2>
            <p class="text-xs text-slate-400 mt-1">Scan QRIS DANA di bawah dan lakukan konfirmasi ke WhatsApp Admin.</p>
        </div>

        <!-- Detail Pesanan -->
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 mb-6 text-xs">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-semibold">ID Transaksi:</span>
                <span class="font-mono font-bold text-slate-900"><?php echo $trx_id; ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-semibold">Produk:</span>
                <span class="font-bold text-slate-900 text-right truncate max-w-[200px]"><?php echo $product->name ?? 'Produk PPOB'; ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-semibold">Nomor Tujuan:</span>
                <span class="font-mono font-bold text-blue-600"><?php echo $target_no; ?></span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                <span class="text-slate-700 font-extrabold uppercase">Total Tagihan:</span>
                <span class="text-base font-black text-emerald-600">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
            </div>
        </div>

        <!-- QRIS DANA Bisnis Mosandy cell -->
        <div class="text-center space-y-4">
            <div class="bg-white p-4 inline-block rounded-3xl border-2 border-slate-200 shadow-md">
                <div id="qrcode" class="flex justify-center"></div>
                <div class="mt-3 text-center">
                    <p class="text-xs font-black text-slate-900">Mosandy cell</p>
                    <p class="text-[10px] font-mono text-slate-400">NMID: ID1026586951067</p>
                </div>
            </div>

            <!-- Petunjuk Langkah Pembayaran -->
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-left space-y-1.5 text-xs">
                <p class="font-extrabold text-amber-900">⚠️ Langkah Konfirmasi Wajib:</p>
                <ol class="list-decimal list-inside text-[11px] text-amber-800 space-y-1 font-semibold">
                    <li>Scan Kode QRIS DANA di atas dari aplikasi E-Wallet/MBanking.</li>
                    <li>Bayar pas sesuai nominal: <b class="text-slate-900">Rp <?php echo number_format($total, 0, ',', '.'); ?></b>.</li>
                    <li>Screenshot / tangkap layar bukti pembayaran.</li>
                    <li>Klik tombol hijau di bawah untuk konfirmasi ke WA Admin.</li>
                </ol>
            </div>

            <?php
                $waAdmin = "6287774802175";
                $pesanWa = "Halo Admin MOSANDY STORE, saya sudah melakukan pembayaran via QRIS DANA.\n\n"
                         . "*Detail Transaksi:*\n"
                         . "• ID Transaksi: " . $trx_id . "\n"
                         . "• Produk: " . ($product->name ?? 'Produk PPOB') . "\n"
                         . "• Nomor Tujuan: " . $target_no . "\n"
                         . "• Total Bayar: Rp " . number_format($total, 0, ',', '.') . "\n\n"
                         . "Berikut saya lampirkan screenshot bukti pembayarannya. Mohon segera diproses. Terima kasih!";
                $urlWa = "https://wa.me/" . $waAdmin . "?text=" . urlencode($pesanWa);
            ?>

            <!-- Tombol Kirim Bukti ke WhatsApp Admin -->
            <a href="<?php echo $urlWa; ?>" target="_blank" 
                class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 rounded-2xl text-xs shadow-lg shadow-emerald-500/25 transition">
                📲 KONFIRMASI BUKTI BAYAR VIA WA &rarr;
            </a>

            <a href="/cek-pesanan?q=<?php echo $trx_id; ?>" class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold py-3 rounded-2xl text-xs transition">
                Cek Status Pesanan
            </a>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs font-bold text-slate-400 hover:text-slate-600">&larr; Batal & Kembali ke Store</a>
        </div>
    </div>

    <!-- Generate QRIS Payload Mosandy Cell DANA Bisnis -->
    <script type="text/javascript">
        const danaQrisPayload = "00020101021126670016ID.CO.QRIS.WWW01189360091100223591290215ID10265869510670303UMI51440014ID.QRIS.WWW0215ID10265869510675204581253033605802ID5912Mosandy cell6007JAKARTA63041C34";
        new QRCode(document.getElementById("qrcode"), {
            text: danaQrisPayload,
            width: 200,
            height: 200
        });
    </script>

</body>
</html>
