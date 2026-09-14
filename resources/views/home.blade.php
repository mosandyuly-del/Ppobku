<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSANDY STORE - Layanan PPOB & Top Up Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-600 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wide">MOSANDY STORE</h1>
            <div>
                <a href="/login" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold text-sm">Masuk / Admin</a>
            </div>
        </div>
    </nav>

    <div class="bg-blue-500 text-white text-center py-8 px-4">
        <h2 class="text-2xl font-bold mb-2">Pusat Isi Ulang & PPOB Terpercaya</h2>
        <p class="text-sm opacity-90">Transaksi serba cepat, otomatis, dan 24 jam non-stop.</p>
    </div>

    <!-- Menu PPOB Lengkap -->
    <div class="container mx-auto px-4 -mt-6">
        <div class="bg-white rounded-xl shadow-lg p-6 grid grid-cols-3 sm:grid-cols-6 gap-4 text-center">
            
            <a href="/category/game" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-purple-600 group-hover:text-white transition">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">Top Up Game</span>
            </a>

            <a href="/category/pulsa" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">Pulsa</span>
            </a>

            <a href="/category/data" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">Paket Data</span>
            </a>

            <a href="/category/pln-token" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-yellow-600 group-hover:text-white transition">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">Token Listrik</span>
            </a>

            <a href="/category/pln-bill" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-amber-600 group-hover:text-white transition">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">Tagihan Listrik</span>
            </a>

            <a href="/category/pdam" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-cyan-100 text-cyan-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-cyan-600 group-hover:text-white transition">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <span class="text-xs font-semibold mt-2 text-gray-700">PDAM</span>
            </a>

        </div>
    </div>

    <footer class="text-center py-6 text-xs text-gray-500 mt-8">
        &copy; 2026 MOSANDY STORE. All Rights Reserved.
    </footer>

</body>
</html>
