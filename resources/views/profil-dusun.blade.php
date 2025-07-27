@extends('layouts.app')

@section('title', 'Profil Interaktif | WebGIS Dusun Boto')

@section('styles')
    {{-- Mapbox GL CSS --}}
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow: hidden;
            height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        /* Main Container */
        .app-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        /* Story Map Location Section */
        .location-story-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 5;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 2s ease;
        }

        .location-story-section.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .location-story-content {
            max-width: 800px;
            padding: 48px;
            text-align: center;
            color: white;
        }

        .location-story-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .location-story-subtitle {
            font-size: 1.2rem;
            margin-bottom: 32px;
            color: #cbd5e1;
        }

        .location-story-description {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 40px;
            color: #e2e8f0;
        }

        .start-journey-btn {
            padding: 16px 32px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .start-journey-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }

        /* Animated Background */
        .animated-background {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 50%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 1;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Particle System */
        .particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 2;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(59, 130, 246, 0.6);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-10px) translateX(100px);
                opacity: 0;
            }
        }

        /* Map Container */
        #map {
            position: absolute;
            inset: 0;
            z-index: 10;
        }

        /* Progress Bar */
        .progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 30;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #10b981);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Loading Screen */
        .loading-screen {
            position: absolute;
            inset: 0;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            opacity: 1;
            transition: opacity 1s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader {
            width: 64px;
            height: 64px;
            border: 3px solid #3b82f6;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: white;
            font-size: 18px;
            font-weight: 500;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        /* Controls */
        .controls {
            position: absolute;
            top: 24px;
            right: 24px;
            display: flex;
            gap: 12px;
            z-index: 30;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideInControls 1s ease 2s forwards;
        }

        @keyframes slideInControls {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .control-btn {
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .control-btn.active {
            background: rgba(59, 130, 246, 0.3);
            border-color: rgba(59, 130, 246, 0.5);
        }

        /* Story Panel */
        .story-panel {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 100%;
            max-width: 500px;
            overflow-y: auto;
            z-index: 20;
            opacity: 0;
            transform: translateX(-100px);
            animation: slideInPanel 1s ease 0.5s forwards;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth;
        }

        .story-panel::-webkit-scrollbar {
            display: none;
        }

        @keyframes slideInPanel {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .story-content {
            padding: 24px;
        }

        /* Chapter Sections */
        .chapter-section {
            margin-bottom: 80vh;
            padding: 32px;
            border-radius: 16px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.7s ease;
            opacity: 0;
            transform: translateY(50px);
        }

        .chapter-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .chapter-section.active {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: scale(1.02);
        }

        .chapter-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .chapter-icon {
            padding: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.8s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .chapter-section.active .chapter-icon {
            transform: rotate(360deg) scale(1.1);
        }

        .chapter-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 4px;
            transition: color 0.5s ease;
        }

        .chapter-subtitle {
            font-size: 14px;
            color: #93c5fd;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chapter-description {
            color: #e5e7eb;
            line-height: 1.7;
            font-size: 18px;
        }

        .active-indicator {
            margin-top: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .chapter-section.active .active-indicator {
            opacity: 1;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulseDot 1s ease-in-out infinite;
        }

        @keyframes pulseDot {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.5);
            }
        }

        /* Navigation Dots */
        .nav-dots {
            position: fixed;
            right: 32px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 16px;
            z-index: 30;
            opacity: 0;
            transform: translateY(-50%) translateX(50px);
            animation: slideInDots 1s ease 3s forwards;
        }

        @keyframes slideInDots {
            to {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }
        }

        .nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-dot.active {
            background: white;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            transform: scale(1.25);
        }

        .nav-dot:hover {
            background: rgba(255, 255, 255, 0.5);
            transform: scale(1.2);
        }

        /* Final CTA */
        .final-cta {
            padding: 32px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .cta-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 24px;
            color: #60a5fa;
            animation: rotateIcon 10s linear infinite;
        }

        @keyframes rotateIcon {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .cta-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
        }

        .cta-button {
            padding: 12px 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }

        .cta-button:active {
            transform: scale(0.95);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .story-panel {
                width: 100%;
                background: rgba(15, 23, 42, 0.9);
            }

            .chapter-section {
                margin: 0 16px 80vh 16px;
                padding: 24px;
            }

            .nav-dots {
                display: none;
            }

            .controls {
                top: 16px;
                right: 16px;
                gap: 8px;
            }

            .location-story-content {
                padding: 32px 24px;
            }

            .location-story-title {
                font-size: 2.5rem;
            }
        }

        /* Color Themes for Chapters */
        .chapter-location .chapter-icon {
            background: linear-gradient(135deg, #10b981, #14b8a6);
        }

        .chapter-location.active .chapter-title {
            color: #10b981;
        }

        .chapter-location .active-indicator {
            color: #10b981;
        }

        .chapter-location .pulse-dot {
            background-color: #10b981;
        }

        .chapter-welcome .chapter-icon {
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
        }

        .chapter-welcome.active .chapter-title {
            color: #3b82f6;
        }

        .chapter-welcome .active-indicator {
            color: #3b82f6;
        }

        .chapter-welcome .pulse-dot {
            background-color: #3b82f6;
        }

        .chapter-boundaries .chapter-icon {
            background: linear-gradient(135deg, #10b981, #14b8a6);
        }

        .chapter-boundaries.active .chapter-title {
            color: #10b981;
        }

        .chapter-boundaries .active-indicator {
            color: #10b981;
        }

        .chapter-boundaries .pulse-dot {
            background-color: #10b981;
        }

        .chapter-roads .chapter-icon {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .chapter-roads.active .chapter-title {
            color: #f59e0b;
        }

        .chapter-roads .active-indicator {
            color: #f59e0b;
        }

        .chapter-roads .pulse-dot {
            background-color: #f59e0b;
        }

        .chapter-facilities .chapter-icon {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
        }

        .chapter-facilities.active .chapter-title {
            color: #8b5cf6;
        }

        .chapter-facilities .active-indicator {
            color: #8b5cf6;
        }

        .chapter-facilities .pulse-dot {
            background-color: #8b5cf6;
        }
    </style>
@endsection

@section('content')
    <div class="app-container">
        <div class="animated-background"></div>

        <div class="particles" id="particles"></div>

        <!-- Location Story Section -->
        <div class="location-story-section" id="locationStorySection">
            <div class="location-story-content">
                <h1 class="location-story-title">Lokasi Dusun Boto</h1>
                <p class="location-story-subtitle">Perjalanan Visual Menuju Jantung Desa Sumberarum</p>
                <p class="location-story-description">
                    Bersiaplah untuk menjelajahi keindahan geografis Dusun Boto melalui pengalaman pemetaan interaktif yang menakjubkan.
                    Dari tingkat kecamatan hingga detail per RW, saksikan transformasi visual yang akan membawa Anda memahami
                    struktur wilayah dengan cara yang belum pernah ada sebelumnya.
                </p>
                <button class="start-journey-btn" onclick="startLocationJourney()">
                    Mulai Perjalanan
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: 8px;">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <div class="loading-screen" id="loadingScreen">
            <div>
                <div class="loader"></div>
                <div class="loading-text">Memuat Peta Interaktif...</div>
            </div>
        </div>

        <div id="map"></div>

        <div class="controls">
            <button class="control-btn" id="playBtn" title="Play/Pause Auto Scroll">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <polygon points="5,3 19,12 5,21"></polygon>
                </svg>
            </button>
            <button class="control-btn" id="resetBtn" title="Reset to Beginning">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <polyline points="1,4 1,10 7,10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
            </button>
        </div>

        <div class="story-panel" id="storyPanel">
            <div class="story-content">
                <section class="chapter-section chapter-location" data-chapter="0">
                    <div class="chapter-header">
                        <div class="chapter-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <h3 class="chapter-title">Lokasi Strategis</h3>
                            <p class="chapter-subtitle">Kecamatan Tempuran Overview</p>
                        </div>
                    </div>
                    <p class="chapter-description">
                        Mulai dari pandangan luas Kecamatan Tempuran, saksikan bagaimana Dusun Boto terletak strategis
                        di dalam struktur administratif yang lebih besar. Zoom bertahap akan membawa Anda menyelami
                        setiap lapisan geografis dengan detail yang menakjubkan.
                    </p>
                    <div class="active-indicator">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10"></polygon>
                        </svg>
                        <span>Chapter Aktif</span>
                        <div class="pulse-dot"></div>
                    </div>
                </section>

                <section class="chapter-section chapter-welcome" data-chapter="1">
                    <div class="chapter-header">
                        <div class="chapter-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h3 class="chapter-title">Selamat Datang</h3>
                            <p class="chapter-subtitle">Dusun Boto Interactive Experience</p>
                        </div>
                    </div>
                    <p class="chapter-description">
                        Mulailah perjalanan visual yang menakjubkan melalui Dusun Boto. Gulir ke bawah untuk menjelajahi setiap
                        sudut wilayah dengan teknologi pemetaan terdepan, atau tekan tombol play untuk scroll otomatis.
                    </p>
                    <div class="active-indicator">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10"></polygon>
                        </svg>
                        <span>Chapter Aktif</span>
                        <div class="pulse-dot"></div>
                    </div>
                </section>

                <section class="chapter-section chapter-boundaries" data-chapter="2">
                    <div class="chapter-header">
                        <div class="chapter-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <h3 class="chapter-title">Batas Wilayah</h3>
                            <p class="chapter-subtitle">Territorial Boundaries</p>
                        </div>
                    </div>
                    <p class="chapter-description">
                        Jelajahi batas administratif Dusun Boto yang berbatasan dengan Tegalsari di utara dan Petet di
                        selatan. Setiap garis boundary menunjukkan zona administratif yang jelas.
                    </p>
                    <div class="active-indicator">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10"></polygon>
                        </svg>
                        <span>Chapter Aktif</span>
                        <div class="pulse-dot"></div>
                    </div>
                </section>

                <section class="chapter-section chapter-roads" data-chapter="3">
                    <div class="chapter-header">
                        <div class="chapter-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="chapter-title">Jaringan Jalan</h3>
                            <p class="chapter-subtitle">Transportation Network</p>
                        </div>
                    </div>
                    <p class="chapter-description">
                        Sistem jaringan jalan yang menghubungkan Dusun Boto dengan pusat Desa Sumberarum. Infrastruktur
                        vital ini menjadi nadi kehidupan ekonomi dan sosial masyarakat.
                    </p>
                    <div class="active-indicator">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10"></polygon>
                        </svg>
                        <span>Chapter Aktif</span>
                        <div class="pulse-dot"></div>
                    </div>
                </section>

                <section class="chapter-section chapter-facilities" data-chapter="4">
                    <div class="chapter-header">
                        <div class="chapter-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                <path d="M10 6h4"></path>
                                <path d="M10 10h4"></path>
                                <path d="M10 14h4"></path>
                                <path d="M10 18h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="chapter-title">Fasilitas & Peternakan</h3>
                            <p class="chapter-subtitle">Community Infrastructure</p>
                        </div>
                    </div>
                    <p class="chapter-description">
                        Fasilitas vital seperti Balai Dusun dan Masjid menjadi pusat aktivitas. Dilengkapi dengan sebaran
                        kandang ternak yang dikelola masyarakat untuk pemberdayaan ekonomi lokal.
                    </p>
                    <div class="active-indicator">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10"></polygon>
                        </svg>
                        <span>Chapter Aktif</span>
                        <div class="pulse-dot"></div>
                    </div>
                </section>

                <section class="final-cta chapter-section" data-chapter="5">
                    <div class="cta-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="3,11 22,2 13,21 11,13 3,11"></polygon>
                        </svg>
                    </div>
                    <h3 class="cta-title">Terima Kasih!</h3>
                    <p class="chapter-description" style="margin-bottom: 24px;">
                        Anda telah menyelesaikan tur interaktif Dusun Boto.
                        Semoga pengalaman visual ini memberikan pemahaman mendalam
                        tentang wilayah yang kaya potensi ini.
                    </p>
                    <button class="cta-button" onclick="resetToBeginning()">
                        Mulai Ulang Tur
                    </button>
                </section>
            </div>
        </div>

        <div class="nav-dots" id="navDots"></div>

    </div>
@endsection

@section('scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration for each chapter with story map animation
            const chapters = [{
                    // Chapter 0: Kecamatan level view
                    center: [110.179044, -7.553176],
                    zoom: 12,
                    pitch: 0,
                    bearing: 0,
                    layers: ['kecamatan-fill', 'kecamatan-outline']
                },
                {
                    // Chapter 1: Desa level view
                    center: [110.179044, -7.553176],
                    zoom: 14,
                    pitch: 15,
                    bearing: 0,
                    layers: ['kecamatan-fill', 'kecamatan-outline', 'desa-fill', 'desa-outline']
                },
                {
                    // Chapter 2: Dusun level view with boundaries
                    center: [110.179044, -7.553176],
                    zoom: 15.5,
                    pitch: 25,
                    bearing: -15,
                    layers: ['desa-fill', 'desa-outline', 'batas-dusun-fill', 'batas-dusun-outline']
                },
                {
                    // Chapter 3: Roads network
                    center: [110.179044, -7.553176],
                    zoom: 16.5,
                    pitch: 45,
                    bearing: 30,
                    layers: ['batas-dusun-fill', 'batas-dusun-outline', 'jaringan-jalan-layer']
                },
                {
                    // Chapter 4: Facilities and livestock
                    center: [110.179044, -7.553176],
                    zoom: 17,
                    pitch: 60,
                    bearing: -45,
                    layers: ['batas-dusun-fill', 'batas-dusun-outline', 'jaringan-jalan-layer',
                        'fasilitas-umum-layer', 'kandang-hewan-layer', 'rw-boundaries-fill', 'rw-boundaries-outline'
                    ]
                },
                {
                    // Chapter 5: Final overview with RW boundaries
                    center: [110.179044, -7.553176],
                    zoom: 15,
                    pitch: 0,
                    bearing: 0,
                    layers: ['batas-dusun-fill', 'batas-dusun-outline', 'jaringan-jalan-layer',
                        'fasilitas-umum-layer', 'kandang-hewan-layer', 'rw-boundaries-fill', 'rw-boundaries-outline'
                    ]
                }
            ];

            let activeChapter = 0;
            let isMapLoaded = false;
            let isPlaying = false;
            let map;
            let autoScrollTimeout = null;
            let storyMapAnimationTimeout = null;
            const chapterScrollDuration = 3000;
            const chapterPauseDuration = 5000;

            const storyPanel = document.getElementById('storyPanel');
            const playBtn = document.getElementById('playBtn');
            const resetBtn = document.getElementById('resetBtn');
            const progressFill = document.getElementById('progressFill');
            const chapterSections = document.querySelectorAll('.chapter-section');
            const locationStorySection = document.getElementById('locationStorySection');

            // Story map animation function
            window.startLocationJourney = function() {
                locationStorySection.classList.add('hidden');

                // Start the story map animation sequence
                setTimeout(() => {
                    startStoryMapAnimation();
                }, 1000);
            };

            function startStoryMapAnimation() {
                if (!isMapLoaded) return;

                let animationStep = 0;
                const animationSteps = [
                    // Step 1: Show Kecamatan
                    () => {
                        map.flyTo({
                            center: [110.179044, -7.553176],
                            zoom: 12,
                            pitch: 0,
                            bearing: 0,
                            duration: 3000
                        });
                        showLayers(['kecamatan-fill', 'kecamatan-outline']);
                    },
                    // Step 2: Zoom to Desa
                    () => {
                        map.flyTo({
                            center: [110.179044, -7.553176],
                            zoom: 14,
                            pitch: 15,
                            bearing: 0,
                            duration: 2000
                        });
                        showLayers(['desa-fill', 'desa-outline']);
                    },
                    // Step 3: Zoom to Dusun
                    () => {
                        map.flyTo({
                            center: [110.179044, -7.553176],
                            zoom: 15.5,
                            pitch: 25,
                            bearing: -15,
                            duration: 2000
                        });
                        showLayers(['batas-dusun-fill', 'batas-dusun-outline']);
                        hideLayers(['kecamatan-fill', 'kecamatan-outline', 'desa-fill', 'desa-outline']);
                    },
                    // Step 4: Show RW boundaries
                    () => {
                        map.flyTo({
                            center: [110.179044, -7.553176],
                            zoom: 16.5,
                            pitch: 45,
                            bearing: 30,
                            duration: 2000
                        });
                        showLayers(['rw-boundaries-fill', 'rw-boundaries-outline']);
                    }
                ];

                function executeAnimationStep() {
                    if (animationStep < animationSteps.length) {
                        animationSteps[animationStep]();
                        animationStep++;
                        storyMapAnimationTimeout = setTimeout(executeAnimationStep, 4000);
                    } else {
                        // Animation complete, enable normal interaction
                        setActiveChapter(1);
                    }
                }

                executeAnimationStep();
            }

            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                for (let i = 0; i < 50; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDuration = (Math.random() * 20 + 10) + 's';
                    particle.style.animationDelay = Math.random() * 10 + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            function initializeMap() {
                mapboxgl.accessToken = 'pk.eyJ1IjoicmF5enUyMyIsImEiOiJjbWRpbHJoeXgwZGt4MmpvYTNldGE3Zmp0In0.mjAKsR4ofnAavL6mGdRC1Q';

                map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/dark-v11',
                    center: chapters[0].center,
                    zoom: chapters[0].zoom,
                    pitch: chapters[0].pitch,
                    bearing: chapters[0].bearing
                });

                map.on('load', function() {
                    isMapLoaded = true;
                    setTimeout(() => {
                        document.getElementById('loadingScreen').classList.add('hidden');
                    }, 1000);
                    addMapLayers();
                });
            }

            function addMapLayers() {
                // Add Kecamatan layer
                map.addSource('kecamatan', {
                    type: 'geojson',
                    data: '/geojson/TEMPURAN.geojson' // Assuming you have this file
                });
                map.addLayer({
                    id: 'kecamatan-fill',
                    type: 'fill',
                    source: 'kecamatan',
                    paint: {
                        'fill-color': '#64748b',
                        'fill-opacity': 0.1
                    },
                    layout: { 'visibility': 'none' }
                });
                map.addLayer({
                    id: 'kecamatan-outline',
                    type: 'line',
                    source: 'kecamatan',
                    paint: {
                        'line-color': '#64748b',
                        'line-width': 2,
                        'line-opacity': 0.6
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add Desa layer
                map.addSource('desa', {
                    type: 'geojson',
                    data: '/geojson/SUMBERARUM.geojson'
                });
                map.addLayer({
                    id: 'desa-fill',
                    type: 'fill',
                    source: 'desa',
                    paint: {
                        'fill-color': '#3b82f6',
                        'fill-opacity': 0.2
                    },
                    layout: { 'visibility': 'none' }
                });
                map.addLayer({
                    id: 'desa-outline',
                    type: 'line',
                    source: 'desa',
                    paint: {
                        'line-color': '#3b82f6',
                        'line-width': 2,
                        'line-opacity': 0.8
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add Dusun Boto layer
                map.addSource('batas-dusun', {
                    type: 'geojson',
                    data: '/geojson/BOTO.geojson'
                });
                map.addLayer({
                    id: 'batas-dusun-fill',
                    type: 'fill',
                    source: 'batas-dusun',
                    paint: {
                        'fill-color': '#10b981',
                        'fill-opacity': 0.3
                    },
                    layout: { 'visibility': 'none' }
                });
                map.addLayer({
                    id: 'batas-dusun-outline',
                    type: 'line',
                    source: 'batas-dusun',
                    paint: {
                        'line-color': '#10b981',
                        'line-width': 3,
                        'line-opacity': 0.9
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add RW boundaries
                map.addSource('rw-boundaries', {
                    type: 'geojson',
                    data: '/geojson/RW.geojson' // Assuming you have RW boundary data
                });
                map.addLayer({
                    id: 'rw-boundaries-fill',
                    type: 'fill',
                    source: 'rw-boundaries',
                    paint: {
                        'fill-color': [
                            'case',
                            ['==', ['get', 'RW'], 'RW 01'], '#ef4444',
                            ['==', ['get', 'RW'], 'RW 02'], '#f97316',
                            ['==', ['get', 'RW'], 'RW 03'], '#eab308',
                            '#06b6d4'
                        ],
                        'fill-opacity': 0.4
                    },
                    layout: { 'visibility': 'none' }
                });
                map.addLayer({
                    id: 'rw-boundaries-outline',
                    type: 'line',
                    source: 'rw-boundaries',
                    paint: {
                        'line-color': '#ffffff',
                        'line-width': 2,
                        'line-opacity': 0.8
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add click event for RW boundaries
                map.on('click', 'rw-boundaries-fill', function(e) {
                    const rwName = e.features[0].properties.RW || 'RW';
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(`<h4 style="margin: 0; color: #1f2937;">${rwName}</h4>`)
                        .addTo(map);
                });

                // Add roads layer
                map.addSource('jaringan-jalan', {
                    type: 'geojson',
                    data: '/geojson/JALAN.geojson'
                });
                map.addLayer({
                    id: 'jaringan-jalan-layer',
                    type: 'line',
                    source: 'jaringan-jalan',
                    paint: {
                        'line-color': '#f59e0b',
                        'line-width': 3,
                        'line-opacity': 0.9
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add facilities layer
                map.addSource('fasilitas-umum', {
                    type: 'geojson',
                    data: '/geojson/Fasilitas_Umum_Boto.geojson'
                });
                map.addLayer({
                    id: 'fasilitas-umum-layer',
                    type: 'circle',
                    source: 'fasilitas-umum',
                    paint: {
                        'circle-radius': 8,
                        'circle-color': '#8b5cf6',
                        'circle-stroke-color': 'white',
                        'circle-stroke-width': 2,
                        'circle-opacity': 1,
                        'circle-stroke-opacity': 1
                    },
                    layout: { 'visibility': 'none' }
                });

                // Add livestock layer
                map.addSource('kandang-hewan', {
                    type: 'geojson',
                    data: '/geojson/HEWAN.geojson'
                });
                map.addLayer({
                    id: 'kandang-hewan-layer',
                    type: 'circle',
                    source: 'kandang-hewan',
                    paint: {
                        'circle-radius': 6,
                        'circle-color': '#f97316',
                        'circle-stroke-color': 'white',
                        'circle-stroke-width': 1,
                        'circle-opacity': 1,
                        'circle-stroke-opacity': 1
                    },
                    layout: { 'visibility': 'none' }
                });
            }

            function showLayers(layerIds) {
                layerIds.forEach(layerId => {
                    if (map.getLayer(layerId)) {
                        map.setLayoutProperty(layerId, 'visibility', 'visible');
                    }
                });
            }

            function hideLayers(layerIds) {
                layerIds.forEach(layerId => {
                    if (map.getLayer(layerId)) {
                        map.setLayoutProperty(layerId, 'visibility', 'none');
                    }
                });
            }

            function autoScrollNextChapter() {
                if (!isPlaying) return;

                let nextChapterIndex = activeChapter + 1;
                if (nextChapterIndex >= chapters.length) {
                    nextChapterIndex = 0;
                }

                const targetSection = chapterSections[nextChapterIndex];
                if (targetSection) {
                    const panelHeight = storyPanel.clientHeight;
                    const sectionTop = targetSection.offsetTop;
                    const sectionHeight = targetSection.offsetHeight;
                    const targetScroll = sectionTop - (panelHeight / 2) + (sectionHeight / 2);

                    storyPanel.scrollTo({
                        top: targetScroll,
                        behavior: 'smooth'
                    });

                    setActiveChapter(nextChapterIndex);

                    autoScrollTimeout = setTimeout(() => {
                        autoScrollNextChapter();
                    }, chapterScrollDuration + chapterPauseDuration);
                }
            }

            function startAutoScroll() {
                if (isPlaying) return;

                isPlaying = true;
                playBtn.classList.add('active');

                playBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="6" y="4" width="4" height="16"></rect>
                        <rect x="14" y="4" width="4" height="16"></rect>
                    </svg>
                `;

                autoScrollNextChapter();
            }

            function stopAutoScroll() {
                if (!isPlaying) return;

                isPlaying = false;
                playBtn.classList.remove('active');

                playBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5,3 19,12 5,21"></polygon>
                    </svg>
                `;

                if (autoScrollTimeout) {
                    clearTimeout(autoScrollTimeout);
                    autoScrollTimeout = null;
                }
            }

            function resetToBeginning() {
                stopAutoScroll();
                if (storyMapAnimationTimeout) {
                    clearTimeout(storyMapAnimationTimeout);
                }
                locationStorySection.classList.remove('hidden');
                storyPanel.scrollTop = 0;
                setActiveChapter(0);
            }

            function setActiveChapter(chapterIndex) {
                if (!isMapLoaded || chapterIndex < 0 || chapterIndex >= chapters.length) return;

                if (chapterIndex === activeChapter && map.isMoving()) {
                    return;
                }

                activeChapter = chapterIndex;

                map.flyTo({
                    ...chapters[chapterIndex],
                    duration: 2000,
                    essential: true
                });

                // Hide all layers first
                const allManagedLayers = [
                    'kecamatan-fill', 'kecamatan-outline',
                    'desa-fill', 'desa-outline',
                    'batas-dusun-fill', 'batas-dusun-outline',
                    'jaringan-jalan-layer',
                    'fasilitas-umum-layer', 'kandang-hewan-layer',
                    'rw-boundaries-fill', 'rw-boundaries-outline'
                ];

                hideLayers(allManagedLayers);

                // Show relevant layers for current chapter
                showLayers(chapters[chapterIndex].layers);

                // Update UI
                document.querySelectorAll('.chapter-section').forEach(section => {
                    section.classList.remove('active');
                });
                const activeSectionElement = document.querySelector(`[data-chapter="${activeChapter}"]`);
                if (activeSectionElement) {
                    activeSectionElement.classList.add('active');
                }

                // Update navigation dots
                document.querySelectorAll('.nav-dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === activeChapter);
                });

                // Update progress bar
                const progress = ((activeChapter + 1) / chapters.length) * 100;
                progressFill.style.width = progress + '%';
            }

            // Create navigation dots
            function createNavigationDots() {
                const navDots = document.getElementById('navDots');
                navDots.innerHTML = '';
                chapters.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.className = `nav-dot ${index === 0 ? 'active' : ''}`;
                    dot.addEventListener('click', () => {
                        stopAutoScroll();
                        const targetSection = document.querySelector(`[data-chapter="${index}"]`);
                        if (targetSection) {
                            const panelHeight = storyPanel.clientHeight;
                            const sectionTop = targetSection.offsetTop;
                            const sectionHeight = targetSection.offsetHeight;
                            const targetScroll = sectionTop - (panelHeight / 2) + (sectionHeight / 2);
                            storyPanel.scrollTo({
                                top: targetScroll,
                                behavior: 'smooth'
                            });
                        }
                        setActiveChapter(index);
                    });
                    navDots.appendChild(dot);
                });
            }

            // Setup intersection observer
            function setupIntersectionObserver() {
                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                const chapterIndex = parseInt(entry.target.getAttribute(
                                    'data-chapter'));
                                if (!isNaN(chapterIndex) && activeChapter !== chapterIndex) {
                                    setActiveChapter(chapterIndex);
                                }
                                entry.target.classList.add('visible');
                            }
                        });
                    }, {
                        threshold: 0.5,
                        rootMargin: '-20% 0px -20% 0px'
                    }
                );

                chapterSections.forEach((section) => {
                    observer.observe(section);
                });
            }

            // Setup controls
            function setupControls() {
                playBtn.addEventListener('click', () => {
                    if (isPlaying) {
                        stopAutoScroll();
                    } else {
                        startAutoScroll();
                    }
                });

                resetBtn.addEventListener('click', resetToBeginning);
            }

            // Handle manual scroll
            function setupScrollHandler() {
                let scrollTimeout;

                storyPanel.addEventListener('scroll', () => {
                    if (isPlaying) {
                        stopAutoScroll();
                    }

                    clearTimeout(scrollTimeout);

                    scrollTimeout = setTimeout(() => {
                        const scrollTop = storyPanel.scrollTop;
                        const scrollHeight = storyPanel.scrollHeight - storyPanel.clientHeight;
                        let scrollProgress = 0;
                        if (scrollHeight > 0) {
                            scrollProgress = scrollTop / scrollHeight;
                        }
                        progressFill.style.width = (scrollProgress * 100) + '%';
                    }, 100);
                });
            }

            window.resetToBeginning = resetToBeginning;

            // Initialize everything
            createParticles();
            initializeMap();
            createNavigationDots();
            setupControls();
            setupScrollHandler();

            setTimeout(setupIntersectionObserver, 1000);
        });
    </script>
@endsection
