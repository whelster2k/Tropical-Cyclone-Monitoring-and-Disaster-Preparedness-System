<?php
$title = 'Emergency Resources';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Emergency Resources</h1>
        <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-full">Live Updates</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Emergency Shelters -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Emergency Shelters</h2>
            </div>
            <p class="text-gray-600 mb-4">Find safe evacuation centers and emergency shelters in your area.</p>
            <a href="/pagasa/public/resources/nearby?type=shelter" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Find Nearby</a>
        </div>

        <!-- Medical Facilities -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Medical Facilities</h2>
            </div>
            <p class="text-gray-600 mb-4">Locate hospitals, clinics, and first aid stations.</p>
            <a href="/pagasa/public/resources/nearby?type=medical" class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors">Find Nearby</a>
        </div>

        <!-- Relief Goods -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Relief Goods</h2>
            </div>
            <p class="text-gray-600 mb-4">Find distribution centers for food, water, and essential supplies.</p>
            <a href="/pagasa/public/resources/nearby?type=relief" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition-colors">Find Nearby</a>
        </div>
    </div>

    <!-- Resource Map -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Resource Map</h2>
                <p class="text-gray-600">View all emergency resources in your area</p>
            </div>
            <div class="relative w-full" style="padding-bottom: 50%;">
                <div id="resource-map" class="absolute inset-0">
                    <!-- Map will be initialized here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('resource-map').setView([8.0, 125.0], 7);

    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Function to load resources
    function loadResources() {
        fetch('/pagasa/public/api/resources/nearby')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateResourceMarkers(data.data.resources);
                }
            })
            .catch(error => console.error('Error loading resources:', error));
    }

    // Load resources initially and every 30 seconds
    loadResources();
    setInterval(loadResources, 30000);
});
</script>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 