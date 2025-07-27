\<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'WebGIS Dusun Boto')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Memberi ruang di atas agar konten tidak tertutup navbar */
        body {
            padding-top: 56px; /* Menyesuaikan dengan tinggi navbar */
        }

        /* Styling untuk navbar global dari landing page Anda */
        .navbar {
            background-color: #004422;
        }
        .navbar .nav-link {
            color: white !important;
            margin-right: 15px;
        }
        .navbar .nav-link:hover {
            text-decoration: underline;
        }
    </style>

    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">WebGIS Dusun Boto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-navbar">
                <ul class="navbar-nav ms-auto">
                    {{-- Link diubah menjadi /#... agar bisa kembali ke seksi di halaman utama --}}
                    <li class="nav-item"><a class="nav-link" href="/#tentang">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profil.dusun') }}"">Explore</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#foto">Foto</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#video">Video</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#kontak">Kontak</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark ms-2" href="{{ route('map') }}">Lihat Peta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
