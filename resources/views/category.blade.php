<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan {{ $title }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center max-w-4xl">
            <a href="/" class="text-xl font-bold">MOSANDY STORE</a>
            <a href="/" class="text-sm bg-white text-blue-600 px-3 py-1.5 rounded-lg font-semibold">&larr; Kembali</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Layanan {{ $title }}</h2>

        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($products as $item)
                @php
                    $nama = $item->name ?? 'Produk PPOB';
                    $harga = $item->price ?? 0;
                    $code = $item->code ?? $item->sku ?? $item->id;
                @endphp
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">{{ $nama }}</h3>
                        <p class="text-xs text-gray-500 uppercase">{{ $item->brand ?? $item->category }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-blue-600 font-bold text-sm block">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                        <button onclick="alert('Produk dipilih')" class="mt-1 text-xs bg-blue-600 text-white font-semibold px-4 py-1.5 rounded-lg">
                            Beli
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-6 rounded-xl text-center text-gray-500 shadow-sm">
                    <p class="font-semibold">Produk {{ $title }} Belum Tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
