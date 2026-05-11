<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventaris')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('katalog') }}">
            <i class="bi bi-box-seam"></i> Inventaris
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">

            <!-- Selalu tampil -->
            <a href="{{ route('katalog') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-grid"></i> Katalog
            </a>

            @auth
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-cart"></i> Peminjaman
                </a>

                <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-person"></i> Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>

                <a href="{{ route('register') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-person-plus"></i> Register
                </a>
            @endauth

        </div>
    </div>
</nav>

<!-- Content -->
<div class="container py-4">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>