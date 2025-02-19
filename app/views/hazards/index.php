<?php
$title = 'Hazard Zones';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Weather-Based Hazard Zones</h1>

    <!-- Real-time Weather Info -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Current Weather Conditions - Mindanao Region</h2>
            <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-full animate-pulse">Live Updates</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="weather-info">
            <div class="text-center p-4 bg-gray-50 rounded-lg transform transition-all duration-300 hover:scale-105">
                <p class="text-sm text-gray-600 mb-2">Rainfall</p>
                <p class="text-2xl font-bold text-gray-800 transition-all duration-300">
                    <span id="rainfall" class="inline-block transform transition-all duration-300">52</span>
                    <span class="text-base ml-1">mm/hr</span>
                </p>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2">
                    <div id="rainfall-bar" class="h-1 bg-blue-500 rounded-full transition-all duration-300" style="width: 52%;"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg transform transition-all duration-300 hover:scale-105">
                <p class="text-sm text-gray-600 mb-2">Wind Speed</p>
                <p class="text-2xl font-bold text-gray-800 transition-all duration-300">
                    <span id="wind-speed" class="inline-block transform transition-all duration-300">87</span>
                    <span class="text-base ml-1">km/h</span>
                </p>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2">
                    <div id="wind-speed-bar" class="h-1 bg-yellow-500 rounded-full transition-all duration-300" style="width: 87%;"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg transform transition-all duration-300 hover:scale-105">
                <p class="text-sm text-gray-600 mb-2">Temperature</p>
                <p class="text-2xl font-bold text-gray-800 transition-all duration-300">
                    <span id="temperature" class="inline-block transform transition-all duration-300">30</span>
                    <span class="text-base ml-1">°C</span>
                </p>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2">
                    <div id="temperature-bar" class="h-1 bg-red-500 rounded-full transition-all duration-300" style="width: 60%;"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg transform transition-all duration-300 hover:scale-105">
                <p class="text-sm text-gray-600 mb-2">Humidity</p>
                <p class="text-2xl font-bold text-gray-800 transition-all duration-300">
                    <span id="humidity" class="inline-block transform transition-all duration-300">70</span>
                    <span class="text-base ml-1">%</span>
                </p>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2">
                    <div id="humidity-bar" class="h-1 bg-green-500 rounded-full transition-all duration-300" style="width: 70%;"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <p class="text-sm text-gray-500">Last updated: <span id="weather-timestamp" class="font-medium"><?php 
                date_default_timezone_set('Asia/Manila'); // Philippines timezone (includes Mindanao)
                echo date('h:i:s A'); 
            ?></span></p>
            <div class="flex items-center">
                <span class="animate-ping h-2 w-2 rounded-full bg-green-400 opacity-75 mr-2"></span>
                <span class="text-xs text-gray-500">Updating live</span>
            </div>
        </div>
    </div>

    <!-- Active Alerts -->
    <div id="active-alerts" class="mb-6"></div>

    <!-- Automatic Hazard Zones Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="hazard-zones">
        <div class="col-span-full bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="animate-pulse" style="animation-duration: 500ms;">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Analyzing weather conditions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Level Legend -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Risk Levels</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center space-x-3">
                <span class="inline-block px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Extreme</span>
                <span class="text-gray-600">Immediate action required</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-block px-3 py-1 text-sm rounded-full bg-orange-100 text-orange-800">High</span>
                <span class="text-gray-600">Significant risk present</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-block px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                <span class="text-gray-600">Moderate risk level</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Low</span>
                <span class="text-gray-600">Minimal risk present</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let weatherData = {
        rainfall: 52,
        wind_speed: 87,
        temperature: 30,
        humidity: 70
    };

    // Function to generate random changes in weather
    function updateWeatherData() {
        return {
            rainfall: Math.max(0, Math.min(150, weatherData.rainfall + (Math.random() - 0.5) * 2)),
            wind_speed: Math.max(0, Math.min(200, weatherData.wind_speed + (Math.random() - 0.5) * 3)),
            temperature: Math.max(20, Math.min(40, weatherData.temperature + (Math.random() - 0.5))),
            humidity: Math.max(0, Math.min(100, weatherData.humidity + (Math.random() - 0.5) * 2))
        };
    }

    // Update weather data every 5 seconds
    setInterval(() => {
        weatherData = updateWeatherData();
        updateWeatherInfo(weatherData);
        updateHazardZones(defineHazardZones(weatherData));
    }, 5000);

    // Initial update
    updateWeatherInfo(weatherData);
    updateHazardZones(defineHazardZones(weatherData));

    // WebSocket connection
    const ws = new WebSocket('wss://pagasa-weather-api.ph/ws');
    
    ws.onopen = function() {
        console.log('Connected to weather service');
        // Subscribe to weather updates
        ws.send(JSON.stringify({
            type: 'subscribe',
            channel: 'weather_updates'
        }));
    };

    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        if (data.type === 'weather_update') {
            updateWeatherInfo(data.weather);
            updateHazardZones(defineHazardZones(data.weather));
        }
    };

    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
        // Fallback to polling if WebSocket fails
        startPolling();
    };

    ws.onclose = function() {
        console.log('Disconnected from weather service');
        // Fallback to polling if WebSocket closes
        startPolling();
    };

    function startPolling() {
        // Fallback polling mechanism
        fetchUpdates();
        setInterval(fetchUpdates, 5000);
    }

    function updateWeatherInfo(data) {
        const elements = {
            'rainfall': {
                element: document.getElementById('rainfall'),
                bar: document.getElementById('rainfall-bar'),
                maxValue: 150
            },
            'wind-speed': {
                element: document.getElementById('wind-speed'),
                bar: document.getElementById('wind-speed-bar'),
                maxValue: 200
            },
            'temperature': {
                element: document.getElementById('temperature'),
                bar: document.getElementById('temperature-bar'),
                maxValue: 50
            },
            'humidity': {
                element: document.getElementById('humidity'),
                bar: document.getElementById('humidity-bar'),
                maxValue: 100
            }
        };

        for (const [key, config] of Object.entries(elements)) {
            const currentValue = parseFloat(config.element.textContent);
            const targetValue = parseFloat(data[key.replace('-', '_')] || config.element.textContent);
            
            if (currentValue !== targetValue) {
                // Animate the number
                animateValue(config.element, currentValue, targetValue, 500);
                
                // Animate the progress bar
                const percentage = (targetValue / config.maxValue) * 100;
                config.bar.style.width = `${Math.min(percentage, 100)}%`;
                
                // Add scale effect
                config.element.parentElement.parentElement.classList.add('scale-105');
                setTimeout(() => {
                    config.element.parentElement.parentElement.classList.remove('scale-105');
                }, 500);
            }
        }
        
        // Update timestamp with fade effect
        const timestampElement = document.getElementById('weather-timestamp');
        timestampElement.style.opacity = '0';
        setTimeout(() => {
            const now = new Date();
            const options = {
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true
            };
            timestampElement.textContent = now.toLocaleTimeString('en-US', options);
            timestampElement.style.opacity = '1';
        }, 300);
    }

    function animateValue(element, start, end, duration) {
        const range = end - start;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const currentValue = start + (range * progress);
            element.textContent = Math.round(currentValue * 10) / 10;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    function updateHazardZones(hazards) {
        const hazardZonesContainer = document.getElementById('hazard-zones');
        
        if (!hazards || hazards.length === 0) {
            const defaultWeather = {
                rainfall: 52,
                wind_speed: 87,
                temperature: 30,
                humidity: 70
            };
            
            hazards = defineHazardZones(defaultWeather);
        }

        // Create new hazard zone elements
        const newHazardsHtml = hazards.map(hazard => `
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-semibold text-gray-800">
                            ${hazard.name}
                        </h2>
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full animate-pulse">Live</span>
                    </div>
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 text-sm rounded-full risk-level
                            ${hazard.risk_level === 'extreme' ? 'bg-red-100 text-red-800' :
                            (hazard.risk_level === 'high' ? 'bg-orange-100 text-orange-800' :
                            (hazard.risk_level === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-green-100 text-green-800'))}">
                            ${hazard.risk_level.charAt(0).toUpperCase() + hazard.risk_level.slice(1)} Risk
                        </span>
                        <span class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full ml-2">
                            ${hazard.type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-3">
                        ${hazard.description}
                    </p>
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-gray-700">Affected Areas:</p>
                        <p class="text-sm text-gray-600">${hazard.affected_areas}</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <p class="text-sm font-semibold text-yellow-800">Precautions:</p>
                        <p class="text-sm text-yellow-700">${hazard.precautions}</p>
                    </div>
                </div>
            </div>
        `).join('');

        // Smoothly update the hazard zones with fade effect only
        hazardZonesContainer.style.opacity = '0';
        setTimeout(() => {
            hazardZonesContainer.innerHTML = newHazardsHtml;
            hazardZonesContainer.style.opacity = '1';
        }, 300);
    }

    function defineHazardZones(weather) {
        const hazards = [];
        
        // Location data for Mindanao regions
        const locations = {
            flood: [
                'Davao Region (Davao City, Tagum, Digos)',
                'SOCCSKSARGEN (Cotabato City, General Santos)',
                'Northern Mindanao (Cagayan de Oro, Iligan)',
                'CARAGA (Butuan, Surigao)'
            ],
            wind_damage: [
                'Davao Oriental (Mati, Baganga)',
                'Surigao del Sur (Tandag, Bislig)',
                'Agusan del Norte (Butuan, Cabadbaran)',
                'Zamboanga Peninsula (Zamboanga City, Dipolog)'
            ],
            storm_surge: [
                'Surigao del Norte (Surigao City)',
                'Davao Oriental Coastline',
                'Zamboanga Coast',
                'Maguindanao Coast'
            ],
            landslide: [
                'Bukidnon Highlands',
                'Mt. Apo Region',
                'Davao Mountain Ranges',
                'CARAGA Mountain Areas'
            ]
        };

        // Rainfall-based hazards
        if (weather.rainfall >= 50) {
            hazards.push({
                name: 'Flood Risk Zone',
                type: 'flood',
                risk_level: weather.rainfall >= 100 ? 'extreme' : 
                           (weather.rainfall >= 75 ? 'high' : 'medium'),
                description: `Automatic flood warning due to heavy rainfall (${weather.rainfall} mm/hr).`,
                affected_areas: locations.flood.join(', '),
                precautions: 'Areas prone to flooding should evacuate to higher ground. Monitor water levels in rivers and drainage systems.'
            });
        }

        // Wind-based hazards
        if (weather.wind_speed >= 80) {
            hazards.push({
                name: 'Wind Hazard Zone',
                type: 'wind_damage',
                risk_level: weather.wind_speed >= 120 ? 'extreme' : 
                           (weather.wind_speed >= 100 ? 'high' : 'medium'),
                description: `High wind alert (${weather.wind_speed} km/h).`,
                affected_areas: locations.wind_damage.join(', '),
                precautions: 'Secure loose objects, stay indoors. Coastal areas should be on alert for strong waves.'
            });
        }

        // Combined weather conditions for storm surge
        if (weather.wind_speed >= 100 && weather.rainfall >= 30) {
            hazards.push({
                name: 'Storm Surge Risk Zone',
                type: 'storm_surge',
                risk_level: (weather.wind_speed >= 120 && weather.rainfall >= 50) ? 'extreme' : 'high',
                description: 'Storm surge warning due to combined high winds and rainfall.',
                affected_areas: locations.storm_surge.join(', '),
                precautions: 'Coastal communities should evacuate to designated safe zones. Fishing activities should be suspended.'
            });
        }

        // Landslide risk based on rainfall and humidity
        if (weather.rainfall >= 40 && weather.humidity >= 85) {
            hazards.push({
                name: 'Landslide Risk Zone',
                type: 'landslide',
                risk_level: weather.rainfall >= 80 ? 'extreme' : 
                           (weather.rainfall >= 60 ? 'high' : 'medium'),
                description: 'Landslide risk warning due to sustained rainfall and high humidity.',
                affected_areas: locations.landslide.join(', '),
                precautions: 'Residents in mountainous areas should be ready to evacuate. Monitor ground conditions and soil stability.'
            });
        }

        return hazards;
    }

    function fetchUpdates() {
        try {
            fetch('/pagasa/public/api/hazards/auto-update')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateWeatherInfo(data.data.weather);
                        updateHazardZones(defineHazardZones(data.data.weather));
                    }
                })
                .catch(error => console.error('Error fetching updates:', error));
        } catch (error) {
            console.error('Error updating weather data:', error);
        }
    }
});
</script>

<style>
.transform {
    transition: transform 0.3s ease-in-out;
}
.opacity-transition {
    transition: opacity 0.3s ease-in-out;
}
#hazard-zones {
    transition: opacity 0.3s ease-in-out;
}
</style>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?>