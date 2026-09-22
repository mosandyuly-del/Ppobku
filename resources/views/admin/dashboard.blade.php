<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

        <!-- Header Admin Panel -->
        <div class="flex justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                    Panel Kelola MOSANDY STORE
                </h1>
                <p class="text-xs text-slate-400 mt-1">Sistem Manajemen Transaksi & Katalog Produk Mandiri</p>
            </div>
            <a href="/logout" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-extrabold px-4 py-2 rounded-xl text-xs transition">
                Keluar (Logout)
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Navigasi Menu Admin -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-2 text-xs font-extrabold">
            <button onclick="switchTab('overview')" id="tab-overview" class="tab-btn flex-1 min-w-[120px] py-3 px-4 rounded-xl text-center bg-blue-600 text-white shadow-md transition">
                📊 Overview
            </button>
            <button onclick="switchTab('transactions')" id="tab-transactions" class="tab-btn flex-1 min-w-[120px] py-3 px-4 rounded-xl text-center text-slate-600 hover:bg-slate-100 transition">
                💳 Transaksi ({{ count($recentTrx ?? []) }})
            </button>
            <button onclick="switchTab('add-product')" id="tab-add-product" class="tab-btn flex-1 min-w-[120px] py-3 px-4 rounded-xl text-center text-slate-600 hover:bg-slate-100 transition">
                ➕ Tambah Produk
            </button>
            <button onclick="switchTab('catalog')" id="tab-catalog" class="tab-btn flex-1 min-w-[120px] py-3 px-4 rounded-xl text-center text-slate-600 hover:bg-slate-100 transition">
                📦 Katalog Produk ({{ $totalProducts ?? 0 }})
            </button>
        </div>

        <!-- TAB 1: OVERVIEW -->
        <div id="content-overview" class="tab-content space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-slate-400">Total Katalog Produk</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalProducts ?? 0 }}</h3>
                    <p class="text-[11px] text-emerald-600 font-bold mt-1">✓ {{ $activeProductsCount ?? 0 }} Produk Aktif</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-slate-400">Total Transaksi</p>
                    <h3 class="text-2xl font-black text-blue-600 mt-1">{{ count($recentTrx ?? []) }}</h3>
                    <p class="text-[11px] text-slate-400 font-bold mt-1">Riwayat Pesanan Masuk</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-slate-400">Metode Pembayaran</p>
                    <h3 class="text-base font-black text-emerald-600 mt-1">QRIS DANA (Mosandy cell)</h3>
                    <p class="text-[11px] text-slate-400 font-bold mt-1">Konfirmasi WA: 087774802175</p>
                </div>
            </div>
        </div>

        <!-- TAB 2: TRANSAKSI MASUK -->
        <div id="content-transactions" class="tab-content space-y-4 hidden">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
                <h2 class="text-sm font-extrabold text-slate-900 uppercase">Daftar Transaksi Masuk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold border-b border-slate-200">
                                <th class="p-3">ID Transaksi</th>
                                <th class="p-3">Produk</th>
                                <th class="p-3">Nomor Tujuan</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Aksi Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                            @forelse($recentTrx ?? [] as $trx)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $trx->trx_id }}</td>
                                    <td class="p-3 font-bold text-slate-900">{{ $trx->product_name }}</td>
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $trx->target_no }}</td>
                                    <td class="p-3 font-black text-emerald-600">Rp {{ number_format($trx->price, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        @if($trx->status == 'SUCCESS')
                                            <span class="bg-emerald-100 text-emerald-800 font-black px-2.5 py-1 rounded-full text-[10px]">SUKSES</span>
                                        @elseif($trx->status == 'PENDING')
                                            <span class="bg-amber-100 text-amber-800 font-black px-2.5 py-1 rounded-full text-[10px]">MENUNGGU</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-800 font-black px-2.5 py-1 rounded-full text-[10px]">GAGAL</span>
                                        @endif
                                    </td>
                                    <td class="p-3 flex space-x-1">
                                        <form action="/admin/update-trx-status" method="POST">
                                            @csrf
                                            <input type="hidden" name="trx_id" value="{{ $trx->trx_id }}">
                                            <input type="hidden" name="status" value="SUCCESS">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-2.5 py-1 rounded-lg text-[10px] transition">
                                                ✔ Sukseskan
                                            </button>
                                        </form>
                                        <form action="/admin/update-trx-status" method="POST">
                                            @csrf
                                            <input type="hidden" name="trx_id" value="{{ $trx->trx_id }}">
                                            <input type="hidden" name="status" value="FAILED">
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-extrabold px-2.5 py-1 rounded-lg text-[10px] transition">
                                                ✖ Batalkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400">Belum ada transaksi masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: TAMBAH PRODUK -->
        <div id="content-add-product" class="tab-content space-y-4 hidden">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
                <h2 class="text-sm font-extrabold text-slate-900 uppercase">Tambah Produk Mandiri</h2>
                <form action="/admin/add-product" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Produk (Unik)</label>
                        <input type="text" name="code" placeholder="Contoh: XL-10GB" required 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" placeholder="Contoh: XL Xtra Combo 10 GB 30 Hari" required 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="price" placeholder="Contoh: 25000" required 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="data">Paket Data</option>
                            <option value="pulsa">Pulsa</option>
                            <option value="game">Voucher Game</option>
                            <option value="e-money">E-Money</option>
                            <option value="pln-token">PLN Token</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Brand / Provider</label>
                        <select name="brand" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="telkomsel">Telkomsel</option>
                            <option value="indosat">Indosat</option>
                            <option value="xl">XL</option>
                            <option value="axis">Axis</option>
                            <option value="tri">Tri</option>
                            <option value="smartfren">Smartfren</option>
                            <option value="umum">Umum / Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Keterangan / Masa Aktif</label>
                        <input type="text" name="description" placeholder="Contoh: Masa aktif 30 hari" 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2 md:col-span-3 pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl transition shadow-md shadow-blue-500/20">
                            + Simpan Produk Ke Katalog
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 4: KATALOG PRODUK + FILTER PROVIDER UNTUK ADMIN -->
        <div id="content-catalog" class="tab-content space-y-4 hidden">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase">Daftar Katalog Produk</h2>
                    
                    <!-- Filter Provider Khusus Admin -->
                    <div class="flex items-center space-x-2 text-xs font-bold">
                        <span class="text-slate-400">Filter Brand:</span>
                        <select id="admin_brand_filter" onchange="filterAdminCatalog()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-extrabold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">Semua Provider</option>
                            <option value="telkomsel">Telkomsel</option>
                            <option value="indosat">Indosat</option>
                            <option value="xl">XL</option>
                            <option value="axis">Axis</option>
                            <option value="tri">Tri</option>
                            <option value="smartfren">Smartfren</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold border-b border-slate-200">
                                <th class="p-3">Kode</th>
                                <th class="p-3">Nama Produk</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Brand</th>
                                <th class="p-3">Harga Jual</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                            @forelse($products as $p)
                                <tr class="admin-product-row hover:bg-slate-50" data-brand="{{ strtolower($p->brand ?? 'umum') }}">
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $p->code }}</td>
                                    <td class="p-3 font-bold text-slate-900">{{ $p->name }}</td>
                                    <td class="p-3 uppercase text-[10px]"><span class="bg-slate-100 px-2 py-1 rounded">{{ $p->category }}</span></td>
                                    <td class="p-3 uppercase text-[10px]"><span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold">{{ $p->brand }}</span></td>
                                    <td class="p-3 font-black text-emerald-600">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <form action="/admin/delete-product" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $p->code }}">
                                            <button type="submit" class="text-rose-600 hover:underline text-[11px] font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400">Belum ada produk di database.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('content-' + tabName).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                btn.classList.add('text-slate-600', 'hover:bg-slate-100');
            });

            const activeBtn = document.getElementById('tab-' + tabName);
            activeBtn.classList.remove('text-slate-600', 'hover:bg-slate-100');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        }

        function filterAdminCatalog() {
            let selectedBrand = document.getElementById('admin_brand_filter').value;
            let rows = document.querySelectorAll('.admin-product-row');

            rows.forEach(row => {
                let rowBrand = row.getAttribute('data-brand');
                if (selectedBrand === 'all' || rowBrand === selectedBrand) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }
    </script>

</body>
</html>
