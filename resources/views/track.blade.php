<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between p-4">

    <!-- Header Navbar -->
    <header class="max-w-xl w-full mx-auto py-4 flex justify-between items-center">
        <a href="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-black text-white">M</div>
            <span class="font-extrabold text-slate-900 text-base">MOSANDY STORE</span>
        </a>
        <a href="/" class="text-xs font-bold text-blue-600 hover:underline">&larr; Beranda</a>
    </header>

    <main class="max-w-xl w-full mx-auto my-auto space-y-6">
        
        <!-- Form Pencarian Lacak Pesanan -->
        <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-200">
            <h2 class="text-lg font-extrabold text-slate-900 mb-1">Cek Status Pesanan</h2>
            <p class="text-xs text-slate-400 mb-4">Masukkan ID Transaksi atau Nomor HP Tujuan kamu.</p>

            <form action="/cek-pesanan" method="GET" class="flex gap-2">
                <input type="text" name="q" value="<?php echo e($search ?? ''); ?>" placeholder="Contoh: TRX-17266... atau 08123..." required
                    class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-5 py-3 rounded-xl text-xs shadow-md transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Hasil Pencarian Status -->
        <?php if(isset($search) && !empty($search)): ?>
            <?php if($transaction): ?>
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase">Status Pesanan</span>
                        <?php if($transaction->status == 'SUCCESS'): ?>
                            <span class="bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1 rounded-full text-xs">BERHASIL / SUKSES</span>
                        <?php elseif($transaction->status == 'PENDING'): ?>
                            <span class="bg-amber-100 text-amber-800 font-extrabold px-3 py-1 rounded-full text-xs animate-pulse">MENUNGGU PEMBAYARAN</span>
                        <?php else: ?>
                            <span class="bg-rose-100 text-rose-800 font-extrabold px-3 py-1 rounded-full text-xs">GAGAL / EXPIRED</span>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">ID Transaksi:</span>
                            <span class="font-mono font-bold text-slate-900"><?php echo e($transaction->trx_id); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Produk:</span>
                            <span class="font-bold text-slate-900"><?php echo e($transaction->product_name ?? 'Produk PPOB'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nomor Tujuan:</span>
                            <span class="font-mono font-bold text-blue-600"><?php echo e($transaction->target_no); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Harga:</span>
                            <span class="font-black text-emerald-600">Rp <?php echo e(number_format($transaction->price, 0, ',', '.')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Waktu Transaksi:</span>
                            <span class="text-slate-600 font-medium"><?php echo e($transaction->created_at); ?></span>
                        </div>
                    </div>

                    <?php if($transaction->status == 'PENDING'): ?>
                        <div class="pt-2">
                            <form action="/checkout" method="POST">
                                @csrf
                                <input type="hidden" name="product_code" value="<?php echo e($transaction->product_code); ?>">
                                <input type="hidden" name="target_no" value="<?php echo e($transaction->target_no); ?>">
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs transition">
                                    Lanjutkan Pembayaran &rarr;
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-5 rounded-3xl text-center text-xs font-bold">
                    Transaksi dengan kata kunci "<span class="underline"><?php echo e($search); ?></span>" tidak ditemukan. Periksa kembali ID Transaksi atau Nomor HP kamu.
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </main>

    <footer class="text-center text-[10px] text-slate-400 py-4">
        &copy; <?php echo date('Y'); ?> MOSANDY STORE • All Rights Reserved.
    </footer>

</body>
</html>
