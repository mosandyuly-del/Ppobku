<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen pb-12">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-lg p-4">
        <!-- Header Nav -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <p class="text-xs font-semibold text-slate-400">Panel Kontrol Admin</p>
                <h1 class="text-lg font-extrabold text-slate-800">MOSANDY STORE ⚙️</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-50 text-red-600 text-xs font-bold px-3 py-2 rounded-xl">Logout</button>
            </form>
        </div>

        <!-- Outbound IP Card -->
        <div class="bg-slate-900 text-white p-4 rounded-2xl mb-6">
            <div class="flex justify-between items-center text-xs mb-2">
                <span class="text-slate-400 font-medium">🌐 IP OUTBOUND SERVER RAILWAY</span>
                <span class="text-emerald-400 font-bold bg-emerald-950 px-2 py-0.5 rounded-full">AKTIF</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xl font-extrabold text-emerald-400">152.55.177.56</span>
                <button onclick="navigator.clipboard.writeText('152.55.177.56'); alert('IP Berhasil Disalin!');" class="bg-slate-800 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-700">📋 Salin IP</button>
            </div>
            <p class="text-[10px] text-slate-400 mt-2">Gunakan IP ini untuk di-whitelist pada Dashboard Member Digiflazz.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                <p class="text-[10px] font-bold text-blue-600 uppercase">Total Produk</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">305</p>
            </div>
            <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <p class="text-[10px] font-bold text-indigo-600 uppercase">Total Pesanan</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">1</p>
            </div>
        </div>

        <!-- Form Upload QRIS Baru -->
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Pengaturan QRIS Pembayaran</h3>
            <form action="{{ route('admin.settings.update-qris') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-500 mb-2">QRIS Saat Ini:</label>
                    @php
                        $qrisSetting = \App\Models\Setting::where('key', 'qris_image')->first();
                    @endphp
                    @if($qrisSetting && $qrisSetting->value)
                        <img src="{{ asset('storage/' . $qrisSetting->value) }}" alt="QRIS Mosandy Cell" class="w-40 h-auto border rounded-xl p-1 bg-white shadow-sm">
                    @else
                        <p class="text-xs text-slate-400 italic">Belum ada gambar QRIS yang diunggah.</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="qris_image" class="block text-xs font-medium text-slate-600 mb-1">Pilih File QRIS Baru</label>
                    <input type="file" name="qris_image" id="qris_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold text-xs py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan QRIS Baru
                </button>
            </form>
        </div>

    </div>

</body>
</html>
