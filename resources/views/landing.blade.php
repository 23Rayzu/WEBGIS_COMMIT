@extends('layouts.app')

@section('title', 'Beranda | WebGIS Dusun Boto')

@section('styles')
    <style>
        /* Reset and Base Styles */
        * {
            box-sizing: border-box;
        }

        body {
            padding-top: 0;
            background-color: #fAF6E6;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #2d3748;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(0, 68, 34, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 40px;
            padding: 12px 20px;
            border: 1px solid rgba(250, 246, 230, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: calc(100vw - 40px);
        }

        .nav-menu {
            display: flex;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
            justify-content: center;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: block;
            padding: 12px 18px;
            color: #fAF6E6;
            text-decoration: none;
            border-radius: 28px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-align: center;
        }

        .nav-link:hover {
            background-color: #fAF6E6;
            color: #004422;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .slideshow img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        .slideshow img.active {
            opacity: 1;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 68, 34, 0.7), rgba(0, 68, 34, 0.4));
            z-index: -1;
        }

        .hero-content {
            z-index: 1;
            max-width: 900px;
            padding: 0 24px;
            color: #fAF6E6;
            margin-top: 0;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            margin-bottom: 24px;
            line-height: 1.2;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            opacity: 0;
            animation: fadeInUp 1s ease-out 0.5s forwards;
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            margin-bottom: 40px;
            font-weight: 400;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            animation: fadeInUp 1s ease-out 1s forwards;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 32px;
            background-color: #fAF6E6;
            color: #004422;
            text-decoration: none;
            border-radius: 48px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeInUp 1s ease-out 1.5s forwards;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .hero-cta:hover {
            background-color: #004422;
            color: #fAF6E6;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sections */
        .section {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: #004422;
            margin-bottom: 16px;
            position: relative;
        }

        .section-subtitle {
            font-size: 18px;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Container */
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Cards */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
            margin-bottom: 48px;
        }

        .card-premium {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 68, 34, 0.08);
            border: 1px solid rgba(0, 68, 34, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 68, 34, 0.15);
            border-color: #004422;
        }

        .card-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #004422, #006633);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: #fAF6E6;
            font-size: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #004422;
            margin-bottom: 12px;
        }

        .card-text {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
        }

        /* Profile Section */
        .profile-section {
            background: linear-gradient(135deg, #004422, #006633);
            color: #fAF6E6;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 48px;
            align-items: center;
        }

        .profile-image {
            position: relative;
        }

        .profile-badge {
            background: linear-gradient(135deg, #fAF6E6, #f0e6d2);
            color: #004422;
            padding: 12px 24px;
            border-radius: 24px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(250, 246, 230, 0.3);
        }

        .profile-text {
            font-size: 18px;
            line-height: 1.7;
            text-align: justify;
        }

        /* Toponimi Section */
        .toponimi-card {
            background: #004422;
            color: #fAF6E6;
            padding: 48px;
            border-radius: 16px;
            position: relative;
            box-shadow: 0 8px 30px rgba(0, 68, 34, 0.2);
            margin: 48px 0;
        }

        .toponimi-card::before {
            content: '"';
            font-size: 120px;
            font-family: serif;
            position: absolute;
            top: 20px;
            left: 30px;
            color: rgba(250, 246, 230, 0.1);
            line-height: 1;
        }

        .toponimi-text {
            font-size: 18px;
            line-height: 1.7;
            font-style: italic;
            position: relative;
            z-index: 2;
            margin-bottom: 24px;
            text-align: justify;
        }

        .toponimi-source {
            font-size: 14px;
            color: rgba(250, 246, 230, 0.8);
            text-align: right;
            font-style: normal;
        }

        /* [DIHAPUS] CSS untuk Gallery Carousel lama telah dihapus. */

        /* [BARU] CSS untuk Modern Gallery & Modal */
        .modern-gallery-section {
            background: linear-gradient(135deg, #004422 0%, #00582c 100%);
            color: #fAF6E6;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .modern-gallery-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(250, 246, 230, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(250, 246, 230, 0.07) 0%, transparent 50%);
            pointer-events: none;
        }

        .gallery-header {
            text-align: left;
            margin-bottom: 48px;
            position: relative;
            z-index: 2;
        }

        .gallery-main-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #ffffff, #fAF6E6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gallery-subtitle {
            font-size: 1.1rem;
            color: rgba(250, 246, 230, 0.8);
            font-weight: 400;
        }

        .gallery-cards-container {
            position: relative;
            z-index: 2;
        }

        .gallery-cards-scroll {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            padding: 20px 0 40px 0;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .gallery-cards-scroll::-webkit-scrollbar {
            display: none;
        }

        .gallery-card {
            flex-shrink: 0;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: linear-gradient(145deg, rgba(250, 246, 230, 0.1), rgba(250, 246, 230, 0.05));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(250, 246, 230, 0.2);
        }

        .gallery-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border-color: rgba(250, 246, 230, 0.4);
        }

        .gallery-card.featured {
            width: 240px;
            height: 320px;
        }

        .gallery-card.regular {
            width: 240px;
            height: 320px;
        }

        .gallery-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-card:hover .gallery-card-image {
            transform: scale(1.1);
        }

        .gallery-card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        }

        .gallery-card-category {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            color: #fAF6E6;
            opacity: 0.8;
        }

        .gallery-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .gallery-card.featured .gallery-card-title {
            font-size: 20px;
        }

        .gallery-card-glow {
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #fAF6E6, #008844, #fAF6E6);
            border-radius: 18px;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
            animation: rotate 4s linear infinite;
        }

        .gallery-card:hover .gallery-card-glow {
            opacity: 0.8;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .gallery-navigation {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 32px;
        }

        .gallery-nav-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(250, 246, 230, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(250, 246, 230, 0.2);
            color: #fAF6E6;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .gallery-nav-btn:hover {
            background: rgba(250, 246, 230, 0.2);
            border-color: rgba(250, 246, 230, 0.4);
            transform: scale(1.1);
        }

        .gallery-nav-btn:active {
            transform: scale(0.95);
        }

        .modal-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 24px;
            cursor: pointer;
            z-index: 10000;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-nav-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Map Section */
        .map-container {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 68, 34, 0.1);
            aspect-ratio: 16/9;
            max-width: 1000px;
            margin: 0 auto;
        }

        #profile-map {
            width: 100%;
            height: 100%;
        }

        /* Video Section */
        .video-container {
            background: #004422;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 68, 34, 0.2);
            aspect-ratio: 16/9;
            max-width: 1000px;
            margin: 0 auto;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Comparison Section */
        .comparison-section {
            background: #004422;
            color: #fAF6E6;
        }

        .comparison-container {
            position: relative;
            aspect-ratio: 21/9;
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .comparison-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .comparison-container .image-before {
            clip-path: inset(0 50% 0 0);
            -webkit-clip-path: inset(0 50% 0 0);
        }

        .slider-handle {
            position: absolute;
            top: 0;
            left: 50%;
            width: 4px;
            height: 100%;
            background: #fAF6E6;
            cursor: ew-resize;
            transform: translateX(-50%);
            z-index: 10;
        }

        .slider-handle::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: #fAF6E6;
            border-radius: 50%;
            border: 3px solid #004422;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .slider-handle::after {
            content: '↔';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            color: #004422;
            font-weight: bold;
            z-index: 11;
        }

        /* Contact Section */
        .contact-section {
            background: linear-gradient(135deg, #004422, #006633);
            color: #fAF6E6;
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 32px;
            margin-top: 48px;
        }

        .contact-item {
            background: rgba(250, 246, 230, 0.1);
            padding: 32px;
            border-radius: 16px;
            border: 1px solid rgba(250, 246, 230, 0.2);
        }

        .contact-icon {
            width: 48px;
            height: 48px;
            background: #fAF6E6;
            color: #004422;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .bottom-nav {
                bottom: 15px;
                left: 50%;
                transform: translateX(-50%);
                width: calc(100% - 30px);
                max-width: 500px;
                border-radius: 25px;
                padding: 8px 12px;
            }

            .nav-menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 4px;
            }

            .nav-link {
                padding: 10px 14px;
                font-size: 13px;
                text-align: center;
            }

            .hero-title {
                font-size: clamp(2rem, 8vw, 3rem);
            }

            .profile-content {
                grid-template-columns: 1fr;
                gap: 32px;
                text-align: center;
            }

            .toponimi-card {
                padding: 32px 24px;
            }

            .card-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .comparison-container {
                aspect-ratio: 16/9;
            }

            .gallery-main-title {
                font-size: 2rem;
            }

            .gallery-card.featured {
                width: 280px;
                height: 360px;
            }

            .gallery-card.regular {
                width: 220px;
                height: 300px;
            }

            .gallery-cards-scroll {
                padding-left: 20px;
                padding-right: 20px;
            }

            .modal-nav-btn {
                width: 40px;
                height: 40px;
                font-size: 20px;
                top: calc(50% - 30px);
            }

            #imageModal {
                padding: 10px;
            }

            #modalTitle {
                font-size: 22px !important;
            }

            #modalDescription {
                font-size: 14px !important;
            }

        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            color: #fAF6E6;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0) translateX(-50%);
            }

            40% {
                transform: translateY(-10px) translateX(-50%);
            }

            60% {
                transform: translateY(-5px) translateX(-50%);
            }
        }

        /* Utility Classes */
        .text-gradient {
            background: linear-gradient(135deg, #004422, #006633);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endsection

@section('content')
    <section class="hero-section">
        <div class="slideshow">
            <img src="storage/SLIDE1.png" class="active" alt="WebGIS Dusun Boto">
            <img src="storage/SLIDE1.png" alt="WebGIS Dusun Boto">
            <img src="storage/SLIDE1.png" alt="WebGIS Dusun Boto">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">WebGIS Dusun Boto</h1>
            <p class="hero-subtitle">Sistem Informasi Geospasial Terintegrasi untuk Pemerintah dan Masyarakat Dusun</p>
            <a href="{{ route('profil.dusun') }}" class="hero-cta">
                Jelajahi Sekarang
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="scroll-indicator">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M19 12l-7 7-7-7" />
            </svg>
        </div>
    </section>

    <section id="profil" class="section profile-section">
        <div class="container-custom">
            <div class="profile-content">
                <div class="profile-image">
                    <img src="storage/DYK_Trans.png" alt="Tahukah Kamu"
                        style="width: 100%; max-width: 400px; height: auto;">
                </div>
                <div>
                    <div class="profile-badge">TAHUKAH KAMU?</div>
                    <h2 class="section-title" style="color: #fAF6E6; text-align: left; margin-bottom: 24px;">Profil Dusun
                        Boto</h2>
                    <p class="profile-text">
                        Dusun Boto merupakan salah satu dari 15 Dusun yang terletak di Desa Sumberarum, Kecamatan
                        Tempuran, Kabupaten Magelang. Terletak tepat di sebelah timur laut berbatasan dengan Kecamatan
                        Mortoyudan di
                        sebelah timur. WebGIS ini dibuat untuk memberikan informasi spasial mengenai batas dusun, fasilitas
                        umum,
                        lahan pertanian, dan data lainnya yang bermanfaat bagi masyarakat dan perangkat dusun.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background-color: #fAF6E6;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title">Toponimi</h2>
            </div>
            <div class="toponimi-card">
                <p class="toponimi-text">
                    Menurut cerita dari Kepala Dusun Boto, nama 'Boto' untuk dusun ini diyakini berasal
                    dari pendiri dusun Boto yang mana bernama Mbah Boto yang dahulu kala berasal dari temanggung
                    kali putih setelah itu pindah ke wilayah ini. sebagai leluhur pendiri dusun Boto, nama tersebut
                    tetap melekat sebagai bagian dari identitas dan sejarah Dusun Boto.
                </p>
                <p class="toponimi-source">
                    — Sumber: Cerita Kepala Dusun Boto (Bapak Ahmad Tolkhah Selaku Kadus Boto)
                </p>
            </div>
        </div>
    </section>

    <section id="explore" class="section" style="background-color: #fAF6E6;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title">Demografi & Data Utama</h2>
                <p class="section-subtitle">Informasi statistik dan karakteristik penduduk Dusun Boto</p>
            </div>
            <div class="card-grid">
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="card-title">Populasi</h3>
                    <p class="card-text">Sekitar 385 Jiwa</p>
                </div>
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9,22 9,12 15,12 15,22" />
                        </svg>
                    </div>
                    <h3 class="card-title">Pemilik Bangunan</h3>
                    <p class="card-text">Sekitar 134</p>
                </div>
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </div>
                    <h3 class="card-title">Luas Wilayah</h3>
                    <p class="card-text">Estimasi 41,7 Hektar</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background-color: #004422;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title" style="color: #fAF6E6;">Potensi Unggulan</h2>
                <p class="section-subtitle" style="color: rgba(250, 246, 230, 0.8);">Keunggulan dan kekuatan ekonomi Dusun
                    Boto</p>
            </div>
            <div class="card-grid">
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z" />
                            <path d="M2 17l10 5 10-5" />
                            <path d="M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <h3 class="card-title">Pertanian Subur</h3>
                    <p class="card-text">Mayoritas penduduk adalah petani dengan tanah yang subur dan ideal untuk berbagai
                        komoditas pertanian unggulan.</p>
                </div>
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="card-title">Keberagaman Ekonomi</h3>
                    <p class="card-text">Beragam mata pencaharian dari pedagang, tukang, buruh, hingga wiraswasta yang
                        menciptakan ekosistem ekonomi yang kuat.</p>
                </div>
                <div class="card-premium">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                    </div>
                    <h3 class="card-title">Inovasi Lokal</h3>
                    <p class="card-text">Semangat kewirausahaan dengan produksi keripik singkong dan produk organik sebagai
                        fondasi UMKM lokal.</p>
                </div>
                <div class="card-premium" style="grid-column: 1 / -1; max-width: 400px; margin: 0 auto;">
                    <div class="card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </div>
                    <h3 class="card-title">Kerukunan Warga</h3>
                    <p class="card-text">Semangat gotong royong dan kebersamaan yang tinggi menciptakan komunitas yang
                        solid dan mendukung pembangunan bersama.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background-color: #fAF6E6;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title">Letak Geografis</h2>
            </div>
            <div class="card-premium" style="max-width: 900px; margin: 0 auto; text-align: justify;">
                <p style="font-size: 16px; line-height: 1.7; color: #4a5568;">
                    Dusun Boto terletak pada koordinat geografis yang strategis, dengan perkiraan pusat di sekitar
                    <strong style="color: #004422;">[Koordinat Dusun Boto, misal: -7.5547 LS, 110.1829 BT]</strong>.
                    Wilayah dusun ini sebagian besar merupakan dataran rendah hingga bergelombang dengan ketinggian
                    rata-rata
                    <strong style="color: #004422;">[Ketinggian rata-rata, misal: 250 meter]</strong> di atas permukaan
                    laut.
                    Kondisi geografis ini sangat mendukung sektor pertanian yang menjadi tulang punggung perekonomian warga.
                    Dusun Boto juga dihiasi dengan <strong style="color: #004422;">pepohonan rimbun dan area
                        persawahan</strong>
                    yang menghijau, memberikan suasana asri dan sejuk.
                </p>
            </div>
        </div>
    </section>

    <section id="peta" class="section" style="background-color: #fAF6E6;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title">Peta Lokasi Dusun Boto</h2>
                <p class="section-subtitle">Visualisasi geografis dan batas wilayah administratif</p>
            </div>
            <div class="map-container">
                <div id="profile-map"></div>
            </div>
        </div>
    </section>

    <section class="section" style="background-color: #004422;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title" style="color: #fAF6E6;">Rute Menuju Dusun Boto</h2>
                <p class="section-subtitle" style="color: #fAF6E6;">
                    Panduan rute dari Sekolah Vokasi UGM menuju lokasi Dusun Boto
                </p>
            </div>
            <div class="video-container">
                <video controls class="w-100 h-100" style="border-radius: 16px;">
                    <source src="{{ asset('storage/Rute_Boto_Mukhlish.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>
            </div>
        </div>
    </section>

    <section class="section comparison-section">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title" style="color: #fAF6E6;">Perbandingan Citra & Foto Udara</h2>
                <p class="section-subtitle" style="color: rgba(250, 246, 230, 0.8);">
                    Geser slider untuk melihat perbedaan kualitas dan detail antara citra satelit dan foto udara drone
                </p>
            </div>
            <div class="comparison-container">
                <img src="{{ asset('storage/Foto_Udara.jpg') }}" alt="Foto Udara" class="image-after">
                <img src="{{ asset('storage/Citra_Satelit.jpg') }}" alt="Citra Satelit" class="image-before">
                <div class="slider-handle"></div>
            </div>
            <p style="text-align: center; margin-top: 24px; color: rgba(250, 246, 230, 0.8);">
                <strong>Kiri:</strong> Citra Satelit | <strong>Kanan:</strong> Foto Udara Drone
            </p>
        </div>
    </section>

    <section id="foto" class="modern-gallery-section">
        <div class="container-custom">
            <div class="gallery-header">
                <h2 class="gallery-main-title">Galeri Dusun Boto</h2>
                <p class="gallery-subtitle">Dokumentasi terpilih kehidupan dan fasilitas di Dusun Boto</p>
            </div>

            <div class="gallery-cards-container">
                <div class="gallery-cards-scroll" id="galleryScroll">
                    <div class="gallery-card featured" onclick="openImageModal(this, 0)"
                        data-image="storage/Kantor_Desa.png" data-category="Pemerintahan" data-title="Kantor Kelurahan"
                        data-description="Kantor Kelurahan Sumberarum yang menangani keluhan tingkat desa untuk mengurus administrasi dan keperluan masyarakat.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/Kantor_Desa.png" alt="Kantor Desa" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Pemerintahan</div>
                            <h3 class="gallery-card-title">Kantor Kelurahan</h3>
                        </div>
                    </div>

                    <div class="gallery-card regular" onclick="openImageModal(this, 1)"
                        data-image="storage/Rumah_Kadus.jpeg" data-category="Pemerintahan"
                        data-title="Rumah Kepala Dusun"
                        data-description="Rumah Kepala Dusun sebagai pusat pengaduan masyarakat dan tempat berkumpulnya warga untuk kemajuan dusun.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/Rumah_Kadus.jpeg" alt="Rumah Kepala Dusun" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Pemerintahan</div>
                            <h3 class="gallery-card-title">Rumah Kepala Dusun</h3>
                        </div>
                    </div>

                    <div class="gallery-card regular" onclick="openImageModal(this, 2)"
                        data-image="storage/Lahan_Pertanian.jpg" data-category="Pertanian" data-title="Lahan Pertanian"
                        data-description="Hamparan sawah hijau yang subur menjadi nadi perekonomian dan menghasilkan berbagai komoditas unggulan.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/Lahan_Pertanian.jpg" alt="Lahan Pertanian" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Pertanian</div>
                            <h3 class="gallery-card-title">Lahan Pertanian</h3>
                        </div>
                    </div>

                    <div class="gallery-card regular" onclick="openImageModal(this, 3)"
                        data-image="storage/Masjid_Ihlas.jpg" data-category="Peribadatan" data-title="Masjid Al-Ihlas"
                        data-description="Masjid Al-Ihlas sebagai tempat ibadah dan pusat kegiatan keagamaan yang memperkuat nilai-nilai spiritual masyarakat.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/Masjid_Ihlas.jpg" alt="Masjid Al-Ihlas" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Peribadatan</div>
                            <h3 class="gallery-card-title">Masjid Al-Ihlas</h3>
                        </div>
                    </div>

                    <div class="gallery-card regular" onclick="openImageModal(this, 4)" data-image="storage/Mushola.jpg"
                        data-category="Peribadatan" data-title="Mushola"
                        data-description="Mushola sebagai tempat ibadah tambahan, meski menurut penduduk setempat sudah jarang digunakan untuk kegiatan rutin.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/Mushola.jpg" alt="Mushola" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Peribadatan</div>
                            <h3 class="gallery-card-title">Mushola</h3>
                        </div>
                    </div>

                    <div class="gallery-card regular" onclick="openImageModal(this, 5)"
                        data-image="storage/POS_RONDA.png" data-category="Keamanan" data-title="Pos Ronda"
                        data-description="Pos Ronda sebagai simbol semangat kebersamaan dalam menjaga keamanan dan ketertiban lingkungan dusun.">
                        <div class="gallery-card-glow"></div>
                        <img src="storage/POS_RONDA.png" alt="Pos Ronda" class="gallery-card-image">
                        <div class="gallery-card-content">
                            <div class="gallery-card-category">Keamanan</div>
                            <h3 class="gallery-card-title">Pos Ronda</h3>
                        </div>
                    </div>
                </div>

                <div class="gallery-navigation">
                    <button class="gallery-nav-btn" onclick="scrollGallery('left')" aria-label="Scroll Left">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    <button class="gallery-nav-btn" onclick="scrollGallery('right')" aria-label="Scroll Right">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div id="imageModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.9); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">

        <button onclick="closeImageModal()"
            style="position: absolute; top: 20px; right: 20px; background: rgba(255, 255, 255, 0.2); color: white; border: none; border-radius: 50%; width: 44px; height: 44px; cursor: pointer; z-index: 10001; display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <button onclick="showPreviousImage()" class="modal-nav-btn" style="left: 20px;">&#10094;</button>
        <button onclick="showNextImage()" class="modal-nav-btn" style="right: 20px;">&#10095;</button>

        <div
            style="position: relative; max-width: 1000px; width: 100%; max-height: 90vh; background: #1f2937; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
            <img id="modalImage" style="width: 100%; height: 85%; object-fit: cover; display: block;">
            <div style="padding: 24px 32px; color: #fAF6E6; flex-grow: 1;">
                <div id="modalCategory"
                    style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: #00a261; margin-bottom: 8px; letter-spacing: 1px;">
                </div>
                <h3 id="modalTitle" style="font-size: 28px; font-weight: 700; color: #ffffff; margin-bottom: 12px;"></h3>
                <p id="modalDescription" style="color: #d1d5db; line-height: 1.6; font-size: 16px;"></p>
            </div>
        </div>
    </div>


    <section id="video" class="section" style="background-color: #004422;">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title" style="color: #fAF6E6;">Video Profil Dusun Boto</h2>
                <p class="section-subtitle" style="color: rgba(250, 246, 230, 0.8);">
                    Dokumentasi visual kehidupan dan aktivitas sehari-hari di Dusun Boto
                </p>
            </div>
            <div class="video-container">
                <div class="ratio ratio-16x9 mx-auto" style="max-width: 900px;">
                    <iframe src="https://www.youtube.com/embed/VIDEO_ID" allowfullscreen
                        style="border-radius: 16px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="section contact-section">
        <div class="container-custom">
            <div class="section-header">
                <h2 class="section-title" style="color: #fAF6E6;">Hubungi Kami</h2>
                <p class="section-subtitle" style="color: rgba(250, 246, 230, 0.8);">
                    Informasi kontak dan lokasi Dusun Boto
                </p>
            </div>
            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <h4 style="color: #fAF6E6; margin-bottom: 8px;">Alamat</h4>
                    <p style="color: rgba(250, 246, 230, 0.9);">Dusun Boto, Sumberarum<br>Tempuran, Magelang, Jawa Tengah
                    </p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </div>
                    <h4 style="color: #fAF6E6; margin-bottom: 8px;">Email</h4>
                    <p style="color: rgba(250, 246, 230, 0.9);">dusunboto@example.com</p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </div>
                    <h4 style="color: #fAF6E6; margin-bottom: 8px;">WhatsApp</h4>
                    <p style="color: rgba(250, 246, 230, 0.9);">0812-3456-7890</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <link href="https://unpkg.com/maplibre-gl@4.3.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@4.3.2/dist/maplibre-gl.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // [DIHAPUS] Script untuk Gallery Carousel lama telah dihapus.

            // [BARU] Script untuk Modern Gallery & Modal
            const galleryScroll = document.getElementById('galleryScroll');
            const galleryCards = document.querySelectorAll('.gallery-card');

            window.scrollGallery = function(direction) {
                if (!galleryScroll) return;
                const scrollAmount = galleryScroll.clientWidth * 0.8;
                galleryScroll.scrollBy({
                    left: direction === 'left' ? -scrollAmount : scrollAmount,
                    behavior: 'smooth'
                });
            };

            let autoScrollInterval;

            function startAutoScroll() {
                if (!galleryScroll) return;
                stopAutoScroll();
                autoScrollInterval = setInterval(() => {
                    const maxScroll = galleryScroll.scrollWidth - galleryScroll.clientWidth;
                    if (galleryScroll.scrollLeft >= maxScroll - 1) {
                        galleryScroll.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        scrollGallery('right');
                    }
                }, 4000);
            }

            function stopAutoScroll() {
                if (autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                }
            }

            const gallerySection = document.querySelector('.modern-gallery-section');
            if (gallerySection) {
                const galleryObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) startAutoScroll();
                        else stopAutoScroll();
                    });
                }, {
                    threshold: 0.3
                });
                galleryObserver.observe(gallerySection);

                galleryScroll.addEventListener('mouseenter', stopAutoScroll);
                galleryScroll.addEventListener('mouseleave', startAutoScroll);
                galleryScroll.addEventListener('touchstart', stopAutoScroll, {
                    passive: true
                });
            }

            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalCategory = document.getElementById('modalCategory');
            const modalDescription = document.getElementById('modalDescription');
            let currentImageIndex = 0;

            window.openImageModal = function(el, index) {
                if (!modal) return;
                currentImageIndex = index;
                showImageByIndex(currentImageIndex);
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            window.closeImageModal = function() {
                if (!modal) return;
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };

            function showImageByIndex(index) {
                const card = galleryCards[index];
                if (!card) return;

                modalImage.src = card.dataset.image;
                modalTitle.textContent = card.dataset.title;
                modalCategory.textContent = card.dataset.category;
                modalDescription.textContent = card.dataset.description;
            }

            window.showNextImage = function() {
                currentImageIndex = (currentImageIndex + 1) % galleryCards.length;
                showImageByIndex(currentImageIndex);
            };

            window.showPreviousImage = function() {
                currentImageIndex = (currentImageIndex - 1 + galleryCards.length) % galleryCards.length;
                showImageByIndex(currentImageIndex);
            };

            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeImageModal();
                });

                document.addEventListener('keydown', function(e) {
                    if (modal.style.display === 'flex') {
                        if (e.key === 'Escape') closeImageModal();
                        if (e.key === 'ArrowRight') showNextImage();
                        if (e.key === 'ArrowLeft') showPreviousImage();
                    }
                });
            }

            // Slideshow functionality
            const slides = document.querySelectorAll('.slideshow img');
            if (slides.length > 0) {
                let index = 0;
                setInterval(() => {
                    slides.forEach(slide => slide.classList.remove('active'));
                    index = (index + 1) % slides.length;
                    slides[index].classList.add('active');
                }, 5000);
            }

            // Smooth scrolling for navigation links
            document.querySelectorAll('.bottom-nav .nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        const offsetTop = targetElement.offsetTop - 20;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Map initialization
            const mapContainer = document.getElementById('profile-map');
            if (mapContainer) {
                const profileMap = new maplibregl.Map({
                    container: 'profile-map',
                    style: 'https://api.maptiler.com/maps/basic-v2-dark/style.json?key=HQweAzPc6iVTmSdT1n0z',
                    center: [110.179044, -7.558000],
                    zoom: 13.2,
                    scrollZoom: false,
                });

                profileMap.addControl(new maplibregl.NavigationControl(), 'top-right');

                profileMap.on('load', () => {
                    profileMap.addSource('sumberarum-source', {
                        'type': 'geojson',
                        'data': '{{ asset('geojson/SUMBERARUM.geojson') }}'
                    });
                    profileMap.addLayer({
                        'id': 'sumberarum-fill',
                        'type': 'fill',
                        'source': 'sumberarum-source',
                        'paint': {
                            'fill-color': '#999999',
                            'fill-opacity': 0.3
                        }
                    });
                    profileMap.addLayer({
                        'id': 'sumberarum-outline',
                        'type': 'line',
                        'source': 'sumberarum-source',
                        'paint': {
                            'line-color': '#333333',
                            'line-width': 1.5
                        }
                    });
                    profileMap.addSource('boto-source', {
                        'type': 'geojson',
                        'data': '{{ asset('geojson/BOTO.geojson') }}'
                    });
                    profileMap.addLayer({
                        'id': 'boto-fill-highlight',
                        'type': 'fill',
                        'source': 'boto-source',
                        'paint': {
                            'fill-color': '#006633',
                            'fill-opacity': 0.6
                        }
                    });
                    profileMap.addLayer({
                        'id': 'boto-outline-highlight',
                        'type': 'line',
                        'source': 'boto-source',
                        'paint': {
                            'line-color': '#004422',
                            'line-width': 3
                        }
                    });
                });
            }

            // Image comparison slider
            const container = document.querySelector('.comparison-container');
            if (container) {
                const imageBefore = container.querySelector('.image-before');
                const sliderHandle = container.querySelector('.slider-handle');
                let isDragging = false;

                const updateSlider = (e) => {
                    const containerRect = container.getBoundingClientRect();
                    let x = (e.clientX || e.touches[0].clientX) - containerRect.left;
                    if (x < 0) x = 0;
                    if (x > containerRect.width) x = containerRect.width;
                    const percentage = (x / containerRect.width) * 100;
                    imageBefore.style.clipPath = `inset(0 ${100 - percentage}% 0 0)`;
                    sliderHandle.style.left = `${percentage}%`;
                };

                sliderHandle.addEventListener('mousedown', () => isDragging = true);
                document.addEventListener('mouseup', () => isDragging = false);
                document.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    requestAnimationFrame(() => updateSlider(e));
                });
                sliderHandle.addEventListener('touchstart', (e) => {
                    isDragging = true;
                    e.preventDefault();
                }, {
                    passive: false
                });
                document.addEventListener('touchend', () => isDragging = false);
                document.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    requestAnimationFrame(() => updateSlider(e));
                }, {
                    passive: false
                });
            }

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            const animationObserver = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.card-premium, .gallery-card').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                animationObserver.observe(el);
            });
        });
    </script>
@endsection
