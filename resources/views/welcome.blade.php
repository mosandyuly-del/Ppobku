<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSANDY STORE - PPOB & Digital Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">MOSANDY STORE</h1>
            <a href="/admin" class="text-xs bg-blue-700 px-3 py-1.5 rounded-lg font-bold">Admin Panel</a>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white mb-8 shadow-lg">
            <h2 class="text-2xl font-extrabold">Selamat Datang di MOSANDY STORE</h2>
            <p class="text-xs text-blue-100 mt-1">Layanan Top Up Game, Pulsa, & Paket Data Tercepat 24 Jam.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <a href="/category/pulsa" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:shadow-md transition">
                <span class="text-2xl">📱</span>
                <h3 class="font-bold text-sm mt-2">Pulsa Reguler</h3>
            </a>
            <a href="/category/data" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:shadow-md transition">
                <span class="text-2xl">🌐</span>
                <h3 class="font-bold text-sm mt-2">Paket Data</h3>
            </a>
            <a href="/category/game" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 text-center hover:shadow-md transition">
                <span class="text-2xl">🎮</span>
                <h3 class="font-bold text-sm mt-2">Voucher Game</h3>
            </a>
        </div>
    </main>
</body>
</html>
