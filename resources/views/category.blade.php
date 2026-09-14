<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan {{ $title }} - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold">MOSANDY STORE</a>
            <a href="/" class="text-sm bg-white text-blue-600 px-3 py-1 rounded-lg font-semibold">&larr; Kembali</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Layanan {{ $title }}</h2>

        @if(count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $item)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $item->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $item->brand ?? 'Digiflazz' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-blue-600 font-bold text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <br>
                            <button class="mt-1 text-xs bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">Beli</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-6 rounded-xl text-center text-gray-500">
                <p>Belum ada produk yang tersedia untuk kategori ini.</p>
                <p class="text-xs mt-1 text-gray-400">Pastikan sinkronisasi harga Digiflazz sudah berjalan.</p>
            </div>
        @endif
    </div>
</body>
</html>
