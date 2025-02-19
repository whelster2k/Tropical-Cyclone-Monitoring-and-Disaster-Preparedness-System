<?php
$title = 'Active Tropical Cyclones';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Active Tropical Cyclones</h1>
        <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full animate-pulse">Live Tracking</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Satellite Map -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="relative w-full" style="padding-bottom: 75%;">
                    <div id="cyclone-map" class="absolute inset-0">
                        <!-- Map will be initialized here -->
                    </div>
                </div>
                <div class="p-4 border-t">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Satellite imagery updates every 5 minutes
                        </div>
                        <div class="flex items-center">
                            <span class="animate-ping h-2 w-2 rounded-full bg-green-400 opacity-75 mr-2"></span>
                            <span class="text-xs text-gray-500">Live Updates</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Legend -->
            <div class="bg-white rounded-lg shadow-lg p-4 mt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Map Legend</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-red-500 rounded-full animate-ping"></div>
                        <span class="text-sm text-gray-600">Current Position</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 border-2 border-red-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Forecast Position</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-0.5 bg-red-500 border-dashed"></div>
                        <span class="text-sm text-gray-600">Forecast Track</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-orange-200 opacity-50"></div>
                        <span class="text-sm text-gray-600">Warning Area</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cyclone Information Panel -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Active Cyclones List -->
            <div id="active-cyclones" class="space-y-4">
                <div class="animate-pulse">
                    <div class="flex items-center justify-center h-48">
                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Category Legend -->
            <div class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Cyclone Categories</h3>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Super Typhoon</span>
                        <span class="text-sm text-gray-600">>185 km/h</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Typhoon</span>
                        <span class="text-sm text-gray-600">118-185 km/h</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Tropical Storm</span>
                        <span class="text-sm text-gray-600">62-117 km/h</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Tropical Depression</span>
                        <span class="text-sm text-gray-600"><62 km/h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map container
    const map = L.map('cyclone-map', {
        center: [8.0, 125.0], // Center on Mindanao
        zoom: 6,
        zoomControl: true,
        minZoom: 4,
        maxZoom: 18
    });

    // Add OpenStreetMap base layer
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Add satellite layer from ESRI
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri',
        maxZoom: 19
    });

    // Add rain radar layer
    const rainLayer = L.tileLayer('https://tilecache.rainviewer.com/v2/radar/{time}/256/{z}/{x}/{y}/2/1_1.png', {
        attribution: '© RainViewer',
        opacity: 0.5,
        time: Math.floor(Date.now() / 1000)
    });

    // Add wind layer with animation
    const velocityLayer = L.velocityLayer({
        displayValues: true,
        displayOptions: {
            velocityType: 'Global Wind',
            position: 'bottomleft',
            emptyString: 'No wind data',
            angleConvention: 'bearingCW',
            speedUnit: 'km/h'
        },
        minVelocity: 0,
        maxVelocity: 30,
        velocityScale: 0.005,
        particleMultiplier: 1/300,
        lineWidth: 2,
        particleAge: 90,
        frameRate: 15,
        opacity: 0.97
    }).addTo(map);

    // Add layer controls
    const baseLayers = {
        "OpenStreetMap": osmLayer,
        "Satellite": satelliteLayer
    };

    const overlayLayers = {
        "Rain Radar": rainLayer,
        "Wind Animation": velocityLayer
    };

    L.control.layers(baseLayers, overlayLayers, {
        collapsed: false,
        position: 'topright'
    }).addTo(map);

    // Add scale control
    L.control.scale({
        metric: true,
        imperial: false,
        position: 'bottomleft'
    }).addTo(map);

    // Function to update weather data
    async function updateWeatherData() {
        try {
            // Update rain radar timestamp
            rainLayer.options.time = Math.floor(Date.now() / 1000);
            rainLayer.redraw();

            // Fetch wind data
            const response = await fetch('https://api.openweathermap.org/data/2.5/wind?lat=8.0&lon=125.0&appid=YOUR_API_KEY');
            const windData = await response.json();
            
            // Update wind layer
            if (windData) {
                velocityLayer.setData({
                    data: windData,
                    minVelocity: 0,
                    maxVelocity: 30,
                    velocityScale: 0.005
                });
            }
        } catch (error) {
            console.error('Error updating weather data:', error);
        }
    }

    // Function to update cyclone data
    async function updateCyclones() {
        try {
            const response = await fetch('/pagasa/public/api/cyclones/active');
            const data = await response.json();
            
            if (data.status === 'success') {
                updateCycloneInfo(data.data.cyclones);
                updateCycloneMap(data.data.cyclones);
            }
        } catch (error) {
            console.error('Error fetching cyclone data:', error);
        }
    }

    // Function to update cyclone information display
    function updateCycloneInfo(cyclones) {
        const container = document.getElementById('active-cyclones');
        
        if (!cyclones || cyclones.length === 0) {
            container.innerHTML = `
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-blue-800 font-medium">No Active Tropical Cyclones</p>
                    <p class="text-blue-600 text-sm mt-1">Mindanao region is currently clear of tropical cyclones</p>
                </div>
            `;
            return;
        }

        container.innerHTML = cyclones.map(cyclone => `
            <div class="bg-white rounded-lg shadow-lg p-4 transform transition-all duration-300 hover:scale-102">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">${cyclone.name}</h3>
                        <p class="text-sm text-gray-600">Local Name: ${cyclone.local_name}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full ${getCycloneCategoryClass(cyclone.category)}">
                        ${cyclone.category}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-sm text-gray-600">Maximum Winds</p>
                        <p class="font-semibold text-blue-600">${cyclone.max_winds} km/h</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-sm text-gray-600">Gustiness</p>
                        <p class="font-semibold text-blue-600">${cyclone.gustiness} km/h</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-sm text-gray-600">Movement</p>
                        <p class="font-semibold text-blue-600">${cyclone.movement}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-sm text-gray-600">Pressure</p>
                        <p class="font-semibold text-blue-600">${cyclone.pressure} hPa</p>
                    </div>
                </div>

                <div class="bg-yellow-50 p-3 rounded-lg">
                    <p class="text-sm font-semibold text-yellow-800">Areas Under Warning:</p>
                    <p class="text-sm text-yellow-700">${cyclone.warning_areas.join(', ')}</p>
                </div>

                <div class="mt-3 text-xs text-gray-500 flex justify-between items-center">
                    <span>Last Updated: ${new Date().toLocaleTimeString()}</span>
                    <span class="flex items-center">
                        <span class="animate-ping h-2 w-2 rounded-full bg-green-400 opacity-75 mr-1"></span>
                        Live
                    </span>
                </div>
            </div>
        `).join('');
    }

    // Helper function for cyclone category styling
    function getCycloneCategoryClass(category) {
        switch (category) {
            case 'Super Typhoon':
                return 'bg-red-100 text-red-800';
            case 'Typhoon':
                return 'bg-orange-100 text-orange-800';
            case 'Tropical Storm':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-blue-100 text-blue-800';
        }
    }

    // Function to update cyclone map markers
    function updateCycloneMap(cyclones) {
        // Clear existing markers
        map.eachLayer((layer) => {
            if (layer instanceof L.Marker || layer instanceof L.Path) {
                map.removeLayer(layer);
            }
        });

        // Add new markers and tracks for each cyclone
        cyclones.forEach(cyclone => {
            // Add cyclone position marker with popup
            const marker = L.marker([cyclone.lat, cyclone.lon], {
                icon: L.divIcon({
                    className: 'cyclone-marker',
                    html: `<div class="w-4 h-4 bg-red-500 rounded-full animate-ping"></div>`,
                    iconSize: [16, 16]
                })
            }).addTo(map);

            // Add popup with cyclone information
            marker.bindPopup(`
                <div class="text-sm">
                    <h3 class="font-semibold">${cyclone.name}</h3>
                    <p>Category: ${cyclone.category}</p>
                    <p>Wind Speed: ${cyclone.max_winds} km/h</p>
                    <p>Movement: ${cyclone.movement}</p>
                </div>
            `);

            // Add forecast track with animation
            if (cyclone.forecast_track) {
                const track = L.polyline(cyclone.forecast_track, {
                    color: 'red',
                    weight: 2,
                    dashArray: '5, 10',
                    opacity: 0.8
                }).addTo(map);

                // Animate the track
                animateTrack(track);

                // Add forecast position markers
                cyclone.forecast_track.forEach((position, index) => {
                    if (index > 0) {
                        L.marker(position, {
                            icon: L.divIcon({
                                className: 'forecast-marker',
                                html: `<div class="w-3 h-3 rounded-full border-2 border-red-500"></div>`,
                                iconSize: [12, 12]
                            })
                        }).addTo(map)
                        .bindPopup(`Forecast Position +${index * 6}h`);
                    }
                });
            }

            // Add warning areas polygon with animation
            if (cyclone.warning_area_coordinates) {
                const warningArea = L.polygon(cyclone.warning_area_coordinates, {
                    color: 'orange',
                    fillColor: 'orange',
                    fillOpacity: 0.2,
                    weight: 1
                }).addTo(map);

                // Animate warning area
                animateWarningArea(warningArea);
            }
        });
    }

    // Function to animate track line
    function animateTrack(track) {
        let offset = 0;
        setInterval(() => {
            offset = (offset + 1) % 15;
            track.setStyle({ dashOffset: -offset });
        }, 100);
    }

    // Function to animate warning area
    function animateWarningArea(polygon) {
        let opacity = 0.2;
        let increasing = true;
        setInterval(() => {
            if (increasing) {
                opacity += 0.01;
                if (opacity >= 0.3) increasing = false;
            } else {
                opacity -= 0.01;
                if (opacity <= 0.1) increasing = true;
            }
            polygon.setStyle({ fillOpacity: opacity });
        }, 50);
    }

    // Update data at regular intervals
    updateWeatherData(); // Initial weather update
    updateCyclones(); // Initial cyclone update
    
    setInterval(updateWeatherData, 300000); // Update weather every 5 minutes
    setInterval(updateCyclones, 5000); // Update cyclones every 5 seconds
});

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .cyclone-marker {
        width: 16px;
        height: 16px;
        transition: all 0.3s ease;
    }

    .forecast-marker {
        width: 12px;
        height: 12px;
        transition: all 0.3s ease;
    }

    .leaflet-container {
        background: #a5bfdd;
    }

    .hover\\:scale-102:hover {
        transform: scale(1.02);
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
`;
document.head.appendChild(style);
</script>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 