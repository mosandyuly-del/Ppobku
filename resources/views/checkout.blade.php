<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen p-4 space-y-4 shadow-sm">
        <div class="flex items-center space-x-3 border-b pb-3">
            <a href="javascript:history.back()" class="text-slate-600 font-bold">&larr; Kembali</a>
            <h1 class="text-lg font-extrabold">Konfirmasi Pesanan</h1>
        </div>

        <form action="/checkout" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl space-y-2">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">{{ $product->brand ?? 'PPOB' }}</p>
                <h3 class="text-sm font-extrabold text-slate-900">{{ $product->name }}</h3>
                <p class="text-lg font-black text-blue-600">Rp {{ number_format($product->price_sell ?? $product->price ?? 0, 0, ',', '.') }}</p>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-600">Nomor Tujuan / HP</label>
                <input type="tel" name="phone" value="{{ $phone }}" required placeholder="08xxx"
                       class="w-full p-3 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-600">Metode Pembayaran</label>
                <select name="payment_method" required class="w-full p-3 rounded-xl border border-slate-300 text-sm font-bold outline-none">
                    <option value="qris">QRIS All Payment (Otomatis)</option>
                    <option value="dana">DANA</option>
                    <option value="gopay">GoPay</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-xl shadow-md transition">
                Bayar Sekarang
            </button>
        </form>
    </div>

</body>
</html>
