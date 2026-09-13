<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko PPOB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">⚡ Toko PPOB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Isi Pulsa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('order.check') }}">Cek Status Pesanan</a></li>
                    
                    @auth
                        <li class="nav-item"><a class="nav-link fw-semibold text-warning" href="{{ route('admin.orders') }}">Kelola Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold text-warning" href="{{ route('admin.products') }}">Kelola Produk</a></li>
                        <li class="nav-item ms-lg-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('login') }}">Login Admin</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">
        @yield('content')
    </main>

    <footer class="bg-white text-center text-muted py-3 border-top mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} Toko PPOB Sederhana. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
