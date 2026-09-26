<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MOSANDY STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm bg-slate-800 rounded-3xl p-6 border border-slate-700 shadow-2xl space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-xl font-extrabold text-white">Panel Admin 🔒</h1>
            <p class="text-xs text-slate-400 font-medium">MOSANDY STORE Management System</p>
        </div>

        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-3 rounded-xl font-medium">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="/admin/login" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Email / Username</label>
                <input type="email" name="email" required placeholder="mosandy@admin.com" 
                       class="w-full p-3.5 bg-slate-900 border border-slate-700 rounded-2xl text-xs text-white focus:outline-none focus:border-blue-500 font-semibold">
            </div>

            <div class="space-y-1">
                <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full p-3.5 bg-slate-900 border border-slate-700 rounded-2xl text-xs text-white focus:outline-none focus:border-blue-500 font-semibold">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-3.5 rounded-2xl text-xs shadow-lg shadow-blue-600/30 active:scale-95 transition">
                Masuk ke Panel
            </button>
        </form>
    </div>

</body>
</html>
