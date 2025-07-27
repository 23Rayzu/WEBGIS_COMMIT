@extends('layouts.app')

@section('content')

{{-- Map Container --}}
<div id="mapContainer">
    {{-- Legend Panel --}}
    <div id="legendPanel" class="glass-panel">
        <div class="legend-header">
            <h3 class="legend-title">
                <i class="fas fa-layer-group"></i>
                Peta Interaktif
            </h3>
            <button id="legendToggle" class="legend-toggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="legend-content">
            <div id="legendItems"></div>
        </div>
    </div>

    {{-- Map Controls --}}
    <div class="map-controls">
        <button id="toggleView" class="control-btn primary">
            <i class="fas fa-cube"></i>
            <span>3D View</span>
        </button>

        <button id="resetView" class="control-btn secondary">
            <i class="fas fa-crosshairs"></i>
            <span>Reset</span>
        </button>

        <button id="fullscreen" class="control-btn secondary">
            <i class="fas fa-expand"></i>
            <span>Fullscreen</span>
        </button>
    </div>

    {{-- Search Panel --}}
    <div id="searchPanel" class="glass-panel search-panel">
        <div class="search-input-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari lokasi atau jenis lahan...">
        </div>
    </div>

    {{-- Map Element --}}
    <div id="map"></div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Memuat peta...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #f97316;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #000000 100%);
            overflow: hidden;
        }

        /* Modern Navigation */
        .modern-navbar {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-icon {
            font-size: 1.8rem;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: white !important;
            background: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        /* Map Container */
        #mapContainer {
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            right: 0;
            overflow: hidden;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* Glass Panel Effect */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            z-index: 10;
            transition: all 0.3s ease;
        }

        /* Legend Panel */
        #legendPanel {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 350px;
            max-height: calc(100vh - 120px);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        #legendPanel.collapsed {
            transform: translateX(-320px);
        }

        .legend-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .legend-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .legend-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .legend-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.1);
        }

        .legend-content {
            padding: 1rem;
            max-height: calc(100vh - 240px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .legend-content::-webkit-scrollbar {
            width: 6px;
        }

        .legend-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .legend-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .legend-section {
            margin-bottom: 1.5rem;
        }

        .legend-section-title {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .legend-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .legend-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .legend-item-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .legend-color-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .legend-item:hover .legend-color-box {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .legend-label {
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Modern Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked + .slider {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .toggle-switch input:checked + .slider:before {
            transform: translateX(20px);
        }

        /* Map Controls */
        .map-controls {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            z-index: 10;
        }

        .control-btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
            justify-content: center;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .control-btn.primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .control-btn.primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.6);
        }

        .control-btn.secondary {
            background: var(--glass-bg);
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .control-btn.secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Search Panel */
        .search-panel {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            padding: 1rem;
        }

        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: rgba(255, 255, 255, 0.6);
            z-index: 1;
        }

        #searchInput {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.875rem;
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }

        #searchInput::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        #searchInput:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        /* Loading Overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 41, 59, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transition: opacity 0.5s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        .loading-spinner p {
            font-size: 1rem;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #legendPanel {
                width: 300px;
                top: 10px;
                left: 10px;
            }

            .search-panel {
                width: calc(100% - 40px);
                left: 20px;
                transform: none;
                top: 80px;
            }

            .map-controls {
                top: 140px;
                right: 10px;
            }

            .control-btn {
                min-width: 100px;
                font-size: 0.8rem;
                padding: 0.6rem 0.8rem;
            }

            #mapContainer {
                top: 70px;
            }
        }

        /* Mapbox Popup Styling */
        .maplibregl-popup-content {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 12px !important;
            color: white !important;
            box-shadow: var(--shadow-xl) !important;
        }

        .maplibregl-popup-close-button {
            color: white !important;
            font-size: 1.2rem !important;
        }
    </style>

    <script>
        // Enhanced color mapping based on your reference images
        const modernColorMap = {
            // Updated colors to match your legend exactly
            "Sungai": "#3b82f6", // Blue
            "Perikanan air tawar": "#06b6d4", // Cyan
            "Sawah": "#22d3ee", // Light cyan
            "Sawah Tadah Hujan": "#67e8f9", // Lighter cyan
            "Hutan": "#059669", // Dark green
            "Hutan Rakyat": "#065f46", // Darker green
            "Perkebunan": "#14b8a6", // Teal
            "Kebun Campur": "#10b981", // Green
            "Tegalan/Ladang": "#fbbf24", // Yellow
            "Rumput": "#84cc16", // Lime green
            "Semak Belukar": "#65a30d", // Olive green
            "Vegetasi Non Budidaya Lainnya": "#134e4a", // Dark teal
            "Pemukiman": "#f97316", // Orange
            "Tempat Tinggal": "#dc2626", // Red/maroon
            "Bangunan": "#8b5cf6", // Blue
            "Pekarangan": "#a3e635", // Light green
            "Perdagangan dan Jasa": "#f97316", // Orange
            "Industri & Perdagangan": "#f97316", // Orange
            "Peternakan": "#a16207", // Brown
            "Transportasi": "#7c3aed", // Purple
            "Tempat menarik/Pariwisata": "#c026d3", // Magenta
            "Peribadatan": "#a855f7", // Purple
            "Pendidikan": "#8b5cf6", // Light purple
            "Lahan Terbuka (Tanah Kosong)": "#a8a29e", // Brown/tan
            "Lahan Terbuka": "#a8a29e" // Brown/tan
        };

        const modernBangunanColorMap = {
            "1": "#8b5cf6", // Purple
            "2": "#c038df", // Purple
            "3": "#f97316"  // Orange
        };

        let is3D = false;
        let map;
        let isLegendCollapsed = false;

        // Initialize map with modern styling
        function initializeMap() {
            map = new maplibregl.Map({
                container: 'map',
                style: {
                    version: 8,
                    sources: {
                        'esri-satellite': {
                            type: 'raster',
                            tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'],
                            tileSize: 256,
                            attribution: '© Esri'
                        }
                    },
                    layers: [{
                        id: 'satellite-basemap',
                        type: 'raster',
                        source: 'esri-satellite',
                        minzoom: 0,
                        maxzoom: 22
                    }]
                },
                center: [110.179044, -7.553176],
                zoom: 16,
                pitch: 0,
                bearing: 0,
                antialias: true,
                fadeDuration: 300
            });

            // Add enhanced navigation controls
            map.addControl(new maplibregl.NavigationControl({
                visualizePitch: true,
                showZoom: true,
                showCompass: true
            }), 'bottom-right');

            // Add scale control
            map.addControl(new maplibregl.ScaleControl({
                maxWidth: 100,
                unit: 'metric'
            }), 'bottom-left');

            return map;
        }

        // Enhanced legend creation
        function createModernLegendItem(name, color, layerId, category, isChecked = true) {
            const item = document.createElement('div');
            item.className = 'legend-item';
            item.setAttribute('data-layer', layerId);

            const itemInfo = document.createElement('div');
            itemInfo.className = 'legend-item-info';

            const colorBox = document.createElement('div');
            colorBox.className = 'legend-color-box';
            colorBox.style.backgroundColor = color;

            const label = document.createElement('span');
            label.className = 'legend-label';
            label.textContent = name;

            itemInfo.appendChild(colorBox);
            itemInfo.appendChild(label);

            const toggle = document.createElement('label');
            toggle.className = 'toggle-switch';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = isChecked;

            checkbox.addEventListener('change', (e) => {
                const visibility = e.target.checked ? 'visible' : 'none';
                map.setLayoutProperty(layerId, 'visibility', visibility);

                // Add visual feedback
                item.style.opacity = e.target.checked ? '1' : '0.5';
            });

            const slider = document.createElement('span');
            slider.className = 'slider';

            toggle.appendChild(checkbox);
            toggle.appendChild(slider);

            item.appendChild(itemInfo);
            item.appendChild(toggle);

            return item;
        }

        // Enhanced popup creation
        function createEnhancedPopup(properties, coordinates, type) {
            let content = '';

            if (type === 'bangunan') {
                content = `
                    <div style="padding: 0.5rem;">
                        <h4 style="margin: 0 0 0.5rem 0; color: #f97316; font-size: 1rem;">
                            <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
                            Bangunan
                        </h4>
                        <p style="margin: 0.25rem 0; font-size: 0.875rem;">
                            <strong>ID:</strong> ${properties.OBJECTID}
                        </p>
                        <p style="margin: 0.25rem 0; font-size: 0.875rem;">
                            <strong>Luas:</strong> ${parseFloat(properties.Shape_Area).toFixed(2)} m²
                        </p>
                    </div>
                `;
            } else {
                content = `
                    <div style="padding: 0.5rem;">
                        <h4 style="margin: 0 0 0.5rem 0; color: ${modernColorMap[properties.KETERANGAN] || '#64748b'}; font-size: 1rem;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem;"></i>
                            ${properties.KETERANGAN}
                        </h4>
                        <p style="margin: 0.25rem 0; font-size: 0.875rem;">
                            <strong>Luas:</strong> ${parseFloat(properties.SHAPE_Area).toFixed(2)} m²
                        </p>
                    </div>
                `;
            }

            return new maplibregl.Popup({
                closeButton: true,
                closeOnClick: true,
                maxWidth: '300px'
            })
            .setLngLat(coordinates)
            .setHTML(content);
        }

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', () => {
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Initialize map
            initializeMap();

            map.on('load', () => {
                // Add terrain
                map.addSource('terrain', {
                    type: 'raster-dem',
                    url: 'https://demotiles.maplibre.org/terrain/source.json',
                    tileSize: 256,
                    maxzoom: 14
                });
                map.setTerrain({ source: 'terrain', exaggeration: 1.5 });

                const legendContainer = document.getElementById('legendItems');

                // Load and process data
                Promise.all([
                    fetch('/geojson/PL_Boto_New.geojson').then(res => res.json()),
                    fetch('/geojson/Bangunan_Boto.geojson').then(res => res.json())
                ]).then(([plData, bangunanData]) => {
                    // Process land use data
                    const plLayers = new Set();

                    // Land use section
                    const plSection = document.createElement('div');
                    plSection.className = 'legend-section';

                    const plTitle = document.createElement('div');
                    plTitle.className = 'legend-section-title';
                    plTitle.innerHTML = '<i class="fas fa-seedling"></i> Penggunaan Lahan';
                    plSection.appendChild(plTitle);

                    map.addSource('pl_boto_new', { type: 'geojson', data: plData });

                    plData.features.forEach(feature => {
                        const category = feature.properties.KETERANGAN;
                        if (!category || plLayers.has(category)) return;
                        plLayers.add(category);

                        const layerId = `pl-layer-${category.replace(/\s+/g, '-')}`;
                        const color = modernColorMap[category] || '#64748b';

                        map.addLayer({
                            id: layerId,
                            type: 'fill',
                            source: 'pl_boto_new',
                            filter: ['==', ['get', 'KETERANGAN'], category],
                            paint: {
                                'fill-color': color,
                                'fill-opacity': 0.7
                            }
                        });

                        const legendItem = createModernLegendItem(category, color, layerId, 'penggunaan-lahan');
                        plSection.appendChild(legendItem);
                    });

                    legendContainer.appendChild(plSection);

                    // Buildings section
                    const bangunanSection = document.createElement('div');
                    bangunanSection.className = 'legend-section';

                    const bangunanTitle = document.createElement('div');
                    bangunanTitle.className = 'legend-section-title';
                    bangunanTitle.innerHTML = '<i class="fas fa-building"></i> Bangunan';
                    bangunanSection.appendChild(bangunanTitle);

                    map.addSource('bangunan_boto', { type: 'geojson', data: bangunanData });
                    map.addLayer({
                        id: 'layer-bangunan',
                        type: 'fill',
                        source: 'bangunan_boto',
                        paint: {
                            'fill-color': [
                                'match',
                                ['get', 'OBJECTID'],
                                ...Object.entries(modernBangunanColorMap).flat(),
                                '#8b5cf6'
                            ],
                            'fill-opacity': 0.8,
                            'fill-outline-color': '#ffffff'
                        }
                    });

                    const bangunanLegendItem = createModernLegendItem('Semua Bangunan', '#8b5cf6', 'layer-bangunan', 'bangunan');
                    bangunanSection.appendChild(bangunanLegendItem);
                    legendContainer.appendChild(bangunanSection);

                    // Enhanced click handlers
                    map.on('click', (e) => {
                        // Check buildings first
                        const bangunanFeatures = map.queryRenderedFeatures(e.point, { layers: ['layer-bangunan'] });
                        if (bangunanFeatures.length > 0) {
                            const popup = createEnhancedPopup(bangunanFeatures[0].properties, e.lngLat, 'bangunan');
                            popup.addTo(map);
                            return;
                        }

                        // Check land use
                        const plLayerIds = Array.from(plLayers).map(cat => `pl-layer-${cat.replace(/\s+/g, '-')}`);
                        const plFeatures = map.queryRenderedFeatures(e.point, { layers: plLayerIds });
                        if (plFeatures.length > 0) {
                            const popup = createEnhancedPopup(plFeatures[0].properties, e.lngLat, 'land-use');
                            popup.addTo(map);
                        }
                    });

                    // Change cursor on hover
                    const hoverLayers = ['layer-bangunan', ...Array.from(plLayers).map(cat => `pl-layer-${cat.replace(/\s+/g, '-')}`)];
                    hoverLayers.forEach(layerId => {
                        map.on('mouseenter', layerId, () => {
                            map.getCanvas().style.cursor = 'pointer';
                        });
                        map.on('mouseleave', layerId, () => {
                            map.getCanvas().style.cursor = '';
                        });
                    });

                    // Hide loading overlay
                    setTimeout(() => {
                        loadingOverlay.classList.add('hidden');
                    }, 1000);

                }).catch(error => {
                    console.error("Error loading GeoJSON data:", error);
                    loadingOverlay.innerHTML = `
                        <div class="loading-spinner">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: var(--error-color); margin-bottom: 1rem;"></i>
                            <p>Gagal memuat data peta</p>
                        </div>
                    `;
                });
            });

            // Enhanced controls
            document.getElementById('toggleView').addEventListener('click', () => {
                is3D = !is3D;
                const btn = document.getElementById('toggleView');

                if (is3D) {
                    map.easeTo({
                        pitch: 60,
                        bearing: -20,
                        duration: 1500
                    });
                    btn.innerHTML = '<i class="fas fa-cube"></i><span>2D View</span>';
                    btn.classList.add('active');
                } else {
                    map.easeTo({
                        pitch: 0,
                        bearing: 0,
                        duration: 1500
                    });
                    btn.innerHTML = '<i class="fas fa-map"></i><span>3D View</span>';
                    btn.classList.remove('active');
                }
            });

            document.getElementById('resetView').addEventListener('click', () => {
                map.easeTo({
                    center: [110.179044, -7.553176],
                    zoom: 16,
                    pitch: 0,
                    bearing: 0,
                    duration: 2000
                });
            });

            document.getElementById('fullscreen').addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    document.getElementById('fullscreen').innerHTML = '<i class="fas fa-compress"></i><span>Exit</span>';
                } else {
                    document.exitFullscreen();
                    document.getElementById('fullscreen').innerHTML = '<i class="fas fa-expand"></i><span>Fullscreen</span>';
                }
            });

            // Legend toggle functionality
            document.getElementById('legendToggle').addEventListener('click', () => {
                const panel = document.getElementById('legendPanel');
                const icon = document.querySelector('#legendToggle i');

                isLegendCollapsed = !isLegendCollapsed;

                if (isLegendCollapsed) {
                    panel.classList.add('collapsed');
                    icon.className = 'fas fa-chevron-right';
                } else {
                    panel.classList.remove('collapsed');
                    icon.className = 'fas fa-chevron-left';
                }
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const legendItems = document.querySelectorAll('.legend-item');

                legendItems.forEach(item => {
                    const label = item.querySelector('.legend-label').textContent.toLowerCase();
                    if (label.includes(searchTerm) || searchTerm === '') {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
