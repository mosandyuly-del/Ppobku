<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Top Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold tracking-wide">MOSANDY STORE - Admin Panel</h1>
        <div class="flex items-center space-x-4">
            <span class="text-xs bg-blue-700 px-3 py-1 rounded-full font-semibold">Admin Active</span>
            <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Notification Alert -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 text-sm rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <p class="text-xs text-gray-500 font-bold uppercase">Total Produk</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalProducts }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">Tersedia di database</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <p class="text-xs text-gray-500 font-bold uppercase">Keuntungan Per Produk</p>
                <h3 class="text-2xl font-black text-green-600 mt-1">+Rp 1.500</h3>
                <p class="text-[11px] text-gray-400 mt-1">Margin otomatis Digiflazz</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Aksi Sistem</p>
                    <p class="text-[11px] text-gray-400 mt-1">Sinkronkan ulang katalog Digiflazz</p>
                </div>
                <form action="/admin/sync-now" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 text-xs rounded-xl shadow transition">
                         Sync Digiflazz Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-base">Katalog Produk PPOB Aktif</h3>
                <a href="/" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold">Lihat Tampilan Web Utam &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="p-3">SKU / Kode</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Brand</th>
                            <th class="p-3">Harga Modal</th>
                            <th class="p-3">Harga Jual (+1500)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($products as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-mono text-gray-600">{{ $p->code ?? $p->sku ?? '-' }}</td>
                                <td class="p-3 font-semibold text-gray-800">{{ $p->name }}</td>
                                <td class="p-3"><span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-bold text-[10px]">{{ $p->category }}</span></td>
                                <td class="p-3 text-gray-600">{{ $p->brand }}</td>
                                <td class="p-3 text-gray-500">Rp {{ number_format($p->price_original ?? 0, 0, ',', '.') }}</td>
                                <td class="p-3 font-bold text-green-600">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-400">Belum ada data produk di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
