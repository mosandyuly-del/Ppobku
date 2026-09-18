<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-200">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-white font-black text-2xl shadow-lg shadow-blue-500/30">
                M
            </div>
            <h2 class="text-xl font-extrabold text-slate-900">Login Admin Panel</h2>
            <p class="text-xs text-slate-400 mt-1">Masukkan email & password akun admin MOSANDY STORE</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Email Admin</label>
                <input type="email" name="email" required placeholder="admin@mosandystore.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-lg shadow-blue-500/25 transition">
                Masuk ke Admin Panel &rarr;
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs font-bold text-blue-600 hover:underline">&larr; Kembali ke Beranda Store</a>
        </div>
    </div>

</body>
</html>
