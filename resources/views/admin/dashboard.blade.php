<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Super Dashboard - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Topbar Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center space-x-3">
            <h1 class="text-xl font-bold tracking-wide">MOSANDY STORE</h1>
            <span class="text-[10px] bg-blue-800 text-blue-200 px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Control Panel</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/" target="_blank" class="text-xs bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg font-semibold transition">&larr; Web utama</a>
            <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition shadow">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Notification Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 text-sm rounded-2xl flex items-center justify-between shadow-sm">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
            </div>
        @endif

        <!-- Rekapitulasi Financial Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Saldo Digiflazz</p>
                        <h3 class="text-2xl font-black text-blue-600 mt-1">Rp {{ number_format($digiflazzBalance ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-xl font-bold text-xs">API</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">Status Akun: <span class="font-bold text-green-600">TERKONEKSI</span></p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Katalog Produk</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalProducts }}</h3>
                    </div>
                    <span class="p-2 bg-purple-50 text-purple-600 rounded-xl font-bold text-xs">SKU</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">Aktif: <span class="font-bold text-gray-700">{{ $activeProductsCount }} Produk</span></p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Rata-rata Margin (%)</p>
                        <h3 class="text-2xl font-black text-green-600 mt-1">{{ number_format($avgMarginPercent, 1) }}%</h3>
                    </div>
                    <span class="p-2 bg-green-50 text-green-600 rounded-xl font-bold text-xs">PROFIT</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">Keuntungan per Produk: <span class="font-bold text-gray-700">+Rp 1.500</span></p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Singkronisasi Manual</p>
                    <p class="text-[11px] text-gray-400 mt-1">Tarik harga modal terbaru</p>
                </div>
                <form action="/admin/sync-now" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 text-xs rounded-xl shadow transition">
                         Sync Digiflazz Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Perbandingan Harga Lengkap & Pengaturan Produk -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Tabel Rekapitulasi & Perbandingan Harga Digiflazz</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Membandingkan harga modal Digiflazz dengan harga jual web MOSANDY STORE</p>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="text" id="searchAdmin" onkeyup="filterAdminTable()" placeholder="Cari nama / SKU..." class="px-3 py-1.5 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="adminTable">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="p-3.5">SKU / Kode</th>
                            <th class="p-3.5">Nama Produk</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Harga Modal Digiflazz</th>
                            <th class="p-3.5">Harga Jual Web</th>
                            <th class="p-3.5">Margin / Profit</th>
                            <th class="p-3.5">Margin (%)</th>
                            <th class="p-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($products as $p)
                            @php
                                $modal = $p->price_original ?? 0;
                                $jual = $p->price ?? 0;
                                $profit = $jual - $modal;
                                $marginPercent = $modal > 0 ? ($profit / $modal) * 100 : 0;
                                $statusActive = ($p->status ?? 'active') === 'active';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3.5 font-mono text-gray-600 font-semibold">{{ $p->code ?? $p->sku ?? '-' }}</td>
                                <td class="p-3.5 font-semibold text-gray-800">{{ $p->name }}</td>
                                <td class="p-3.5"><span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-bold text-[10px] uppercase">{{ $p->category }}</span></td>
                                <td class="p-3.5 text-gray-600 font-semibold">Rp {{ number_format($modal, 0, ',', '.') }}</td>
                                <td class="p-3.5 font-bold text-blue-600">Rp {{ number_format($jual, 0, ',', '.') }}</td>
                                <td class="p-3.5 font-bold text-green-600">+Rp {{ number_format($profit, 0, ',', '.') }}</td>
                                <td class="p-3.5"><span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-md font-bold text-[10px]">{{ number_format($marginPercent, 1) }}%</span></td>
                                <td class="p-3.5">
                                    @if($statusActive)
                                        <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-[10px] font-bold">AKTIF</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-[10px] font-bold">GANGGUAN</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-400 font-semibold">Belum ada data produk di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterAdminTable() {
            const input = document.getElementById('searchAdmin').value.toLowerCase();
            const rows = document.querySelectorAll('#adminTable tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>
