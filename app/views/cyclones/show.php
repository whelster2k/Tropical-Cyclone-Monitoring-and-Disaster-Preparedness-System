<?php
$title = htmlspecialchars($cyclone['name']) . ' - Cyclone Details';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/cyclones" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Active Cyclones
        </a>
    </div>

    <!-- Cyclone Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($cyclone['name']); ?></h1>
                    <p class="text-blue-100">Category: <?php echo htmlspecialchars($cyclone['category']); ?></p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">Status</p>
                    <p class="font-semibold"><?php echo ucfirst(htmlspecialchars($cyclone['status'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Current Information -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Wind Speed</p>
                    <p class="text-xl font-semibold"><?php echo number_format($cyclone['wind_speed'], 1); ?> km/h</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pressure</p>
                    <p class="text-xl font-semibold"><?php echo number_format($cyclone['pressure'], 1); ?> hPa</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Formation Date</p>
                    <p class="text-xl font-semibold"><?php echo date('M d, Y', strtotime($cyclone['formation_date'])); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Last Update</p>
                    <p class="text-xl font-semibold"><?php echo date('M d, Y H:i', strtotime($cyclone['last_update'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Tracking Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Map Container -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-4" style="min-height: 400px;">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Cyclone Track Map</h2>
            <div id="cycloneMap" class="w-full h-96 rounded-lg"></div>
        </div>

        <!-- Tracking Points -->
        <div class="bg-white rounded-lg shadow-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tracking History</h2>
            <div class="overflow-y-auto max-h-96">
                <?php if (!empty($cyclone['tracking_points'])): ?>
                    <?php foreach ($cyclone['tracking_points'] as $point): ?>
                        <div class="border-b border-gray-200 py-3">
                            <div class="text-sm">
                                <p class="font-semibold">
                                    <?php echo date('M d, Y H:i', strtotime($point->timestamp)); ?>
                                </p>
                                <p class="text-gray-600">
                                    Location: <?php echo number_format($point->lat, 2); ?>°N, 
                                    <?php echo number_format($point->lng, 2); ?>°E
                                </p>
                                <p class="text-gray-600">
                                    Wind Speed: <?php echo number_format($point->wind_speed, 1); ?> km/h
                                </p>
                                <p class="text-gray-600">
                                    Pressure: <?php echo number_format($point->pressure, 1); ?> hPa
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600 text-center py-4">No tracking points available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Forecast and Warnings</h2>
        <div class="prose max-w-none">
            <p class="text-gray-600">
                This cyclone is currently being monitored by PAGASA. Regular updates will be provided as new information becomes available.
                Please stay tuned to official announcements and follow all evacuation orders if issued.
            </p>
        </div>
    </div>
</div>

<!-- Leaflet.js for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('cycloneMap').setView([
        <?php echo $cyclone['current_lat']; ?>, 
        <?php echo $cyclone['current_lng']; ?>
    ], 6);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add current position marker
    L.marker([
        <?php echo $cyclone['current_lat']; ?>, 
        <?php echo $cyclone['current_lng']; ?>
    ]).addTo(map)
    .bindPopup('Current Position: <?php echo htmlspecialchars($cyclone['name']); ?>');

    // Add tracking points and path
    <?php if (!empty($cyclone['tracking_points'])): ?>
        const trackingPoints = <?php echo json_encode($cyclone['tracking_points']); ?>;
        const pathCoordinates = [];
        
        trackingPoints.forEach(point => {
            pathCoordinates.push([point.lat, point.lng]);
            L.marker([point.lat, point.lng])
                .addTo(map)
                .bindPopup(`
                    Time: ${new Date(point.timestamp).toLocaleString()}<br>
                    Wind Speed: ${point.wind_speed} km/h<br>
                    Pressure: ${point.pressure} hPa
                `);
        });

        // Draw path line
        L.polyline(pathCoordinates, {
            color: 'red',
            weight: 2,
            opacity: 0.7
        }).addTo(map);
    <?php endif; ?>
});
</script>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 