<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Multi-Channel - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <?php if(!empty($client_key) && !empty($snap_token)): ?>
        <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?php echo $client_key; ?>"></script>
    <?php endif; ?>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-200">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">Multichannel Payment 24 Jam</span>
            <h2 class="text-xl font-extrabold text-slate-900">Selesaikan Pembayaran</h2>
            <p class="text-xs text-slate-400 mt-1">Pilih metode pembayaran sesuai keinginan kamu.</p>
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

        <!-- Opsi Metode Pembayaran yang Didukung -->
        <div class="mb-6 space-y-2">
            <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Metode Pembayaran Tersedia:</p>
            <div class="grid grid-cols-2 gap-2 text-[10px] font-bold">
                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <span>QRIS (DANA/OVO)</span>
                    <span class="text-emerald-600">✔</span>
                </div>
                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <span>GoPay / ShopeePay</span>
                    <span class="text-emerald-600">✔</span>
                </div>
                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <span>Virtual Account Bank</span>
                    <span class="text-emerald-600">✔</span>
                </div>
                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <span>Indomaret / Alfamart</span>
                    <span class="text-emerald-600">✔</span>
                </div>
            </div>
        </div>

        <!-- Tombol Pemicu Midtrans Snap -->
        <?php if(!empty($snap_token)): ?>
            <div class="text-center space-y-3">
                <button id="pay-button" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 rounded-2xl text-xs shadow-lg shadow-emerald-500/25 transition">
                    PILIH METODE PEMBAYARAN &rarr;
                </button>
                <p class="text-[10px] text-slate-400">PILIH QRIS, E-WALLET, VA BANK, ATAU MINIMARKET</p>
            </div>
            <script type="text/javascript">
                const payButton = document.getElementById('pay-button');
                payButton.addEventListener('click', function () {
                    snap.pay('<?php echo $snap_token; ?>', {
                        onSuccess: function(result){ window.location.href = "/cek-pesanan?q=<?php echo $trx_id; ?>"; },
                        onPending: function(result){ window.location.href = "/cek-pesanan?q=<?php echo $trx_id; ?>"; },
                        onError: function(result){ alert("Pembayaran gagal!"); }
                    });
                });
                window.onload = function() {
                    payButton.click();
                };
            </script>
        <?php else: ?>
            <div class="text-center space-y-4">
                <div class="bg-white p-4 inline-block rounded-2xl border-2 border-dashed border-blue-200 shadow-sm">
                    <div id="qrcode" class="flex justify-center"></div>
                </div>
                <p class="text-xs font-bold text-slate-700">Scan QRIS All Payment</p>
                <a href="/cek-pesanan?q=<?php echo $trx_id; ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-2xl text-xs transition">
                    Cek Status Pesanan &rarr;
                </a>
            </div>
            <script type="text/javascript">
                const defaultPayload = "00020101021126570011ID.NOBU.WWW011893600503000008807902150000000000000000303UMI51440014ID.QRIS.WWW0215ID10200212345675204581253033605802ID5913MOSANDY STORE6007JAKARTA63046C41";
                new QRCode(document.getElementById("qrcode"), {
                    text: defaultPayload,
                    width: 180,
                    height: 180
                });
            </script>
        <?php endif; ?>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs font-bold text-slate-400 hover:text-slate-600">&larr; Batalkan & Kembali</a>
        </div>
    </div>

</body>
</html>
