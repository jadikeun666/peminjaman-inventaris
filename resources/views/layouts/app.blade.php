<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventaris')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;

        /* Gradasi kuning hangat keemasan */
        background-color: #fef9c3;
        background-image:
            /* Ornamen salib besar — jarang, di sudut-sudut */
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cg fill='%23ca8a04' opacity='0.10'%3E%3Crect x='138' y='30' width='24' height='120' rx='5'/%3E%3Crect x='100' y='68' width='100' height='24' rx='5'/%3E%3C/g%3E%3C/svg%3E"),
            /* Ornamen salib kecil — lebih rapat */
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cg fill='%23ca8a04' opacity='0.09'%3E%3Crect x='64' y='18' width='12' height='60' rx='3'/%3E%3Crect x='44' y='33' width='52' height='12' rx='3'/%3E%3C/g%3E%3Cg fill='none' stroke='%23ca8a04' opacity='0.07' stroke-width='1.2'%3E%3Ccircle cx='70' cy='105' r='8'/%3E%3Ccircle cx='70' cy='105' r='12'/%3E%3C/g%3E%3C/svg%3E"),
            /* Bintang kecil / biji rosario dekoratif */
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Ccircle cx='40' cy='40' r='3' fill='%23ca8a04' opacity='0.10'/%3E%3C/svg%3E"),
            /* Gradasi dasar kuning keemasan */
            linear-gradient(160deg, #fefce8 0%, #fef3c7 55%, #fde68a 100%);

        background-size: 300px 300px, 140px 140px, 80px 80px, cover;
        background-attachment: fixed;
    }

    /* ── Navbar ── */
    .navbar {
        background-color: rgba(13, 110, 253, 0.93) !important;
        backdrop-filter: blur(6px);
    }

    /* ── Konten utama ── */
    .main-content {
        flex: 1;
    }

    /* ── Card tetap putih bersih ── */
    .card {
        background-color: rgba(255, 255, 255, 0.95) !important;
    }

    /* ── Footer: kuning cerah keemasan ── */
    .site-footer {
        background-color: #FCD34D;
        border-top: 3px solid #F59E0B;
        color: #1c1c1c;
        padding: 2rem 0 1rem;
    }

    .site-footer .footer-org-name {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #78350f;
    }

    .site-footer .footer-address {
        font-size: 0.85rem;
        line-height: 1.8;
        color: #44403c;
    }

    .site-footer .footer-divider {
        border-color: rgba(0, 0, 0, 0.15);
    }

    .site-footer .footer-copyright {
        font-size: 0.78rem;
        color: #57534e;
    }

    .site-footer .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.55);
        color: #78350f;
        font-size: 1rem;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .site-footer .social-icon:hover {
        background-color: rgba(255, 255, 255, 0.85);
        color: #92400e;
    }

    .site-footer .cross-icon {
        font-size: 1.8rem;
        color: #92400e;
        opacity: 0.8;
    }
</style>
</head>

<body>

    {{-- ── Navbar ── --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('katalog') }}">
                <i class="bi bi-box-seam"></i> Inventaris
            </a>
            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
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

    {{-- ── Konten halaman ── --}}
    <div class="main-content">
        <div class="container py-4">
            @yield('content')
        </div>
    </div>

    {{-- ── Footer ── --}}
    <footer class="site-footer">
        <div class="container text-center">

            {{-- Logo / Ikon --}}
            <div class="mb-2">
                <i class="bi bi-brightness-high-fill" style="font-size: 2rem; color: #1a1a1a;"></i>
            </div>

            {{-- Nama organisasi --}}
            <p class="footer-org-name mb-1">UKM Katolik Universitas Lampung</p>

            {{-- Alamat --}}
            <p class="footer-address mb-2">
                Kompleks Universitas Lampung, Jl. Prof. Dr. Ir. Sumantri Brojonegoro No. 1,<br>
                Gedong Meneng, Kec. Rajabasa, Bandar Lampung.<br>
                <strong>Graha Kemahasiswaan Lama Lantai 2</strong> (Sekret UKM Katolik Unila)
            </p>

            {{-- Social media --}}
            <div class="d-flex justify-content-center gap-2 mb-3">
                <a href="#" class="social-icon" title="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" class="social-icon" title="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="social-icon" title="YouTube">
                    <i class="bi bi-youtube"></i>
                </a>
                <a href="#" class="social-icon" title="TikTok">
                    <i class="bi bi-tiktok"></i>
                </a>
            </div>

            <hr class="footer-divider">

            <p class="footer-copyright mb-0">
                &copy; {{ date('Y') }} UKM Katolik Universitas Lampung &mdash; Semua Hak Dilindungi.
            </p>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>