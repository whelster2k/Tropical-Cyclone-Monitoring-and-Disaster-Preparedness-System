<?php
$title = 'Hazard Map';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Hazard Map</h1>

    <!-- Map Container -->
    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div id="hazardMap" class="w-full h-[600px] rounded-lg"></div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Map Legend</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center">
                <div class="w-6 h-6 rounded bg-red-500 opacity-50"></div>
                <span class="ml-2 text-gray-600">Extreme Risk Zone</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 rounded bg-orange-500 opacity-50"></div>
                <span class="ml-2 text-gray-600">High Risk Zone</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 rounded bg-yellow-500 opacity-50"></div>
                <span class="ml-2 text-gray-600">Medium Risk Zone</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 rounded bg-green-500 opacity-50"></div>
                <span class="ml-2 text-gray-600">Low Risk Zone</span>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Philippines
    const map = L.map('hazardMap').setView([12.8797, 121.7740], 6);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add hazard zones to map
    <?php foreach ($hazards as $hazard): ?>
        const boundaryPoints = <?php echo $hazard['boundary_points']; ?>;
        const color = <?php echo json_encode(
            $hazard['risk_level'] === 'extreme' ? '#ef4444' :
            ($hazard['risk_level'] === 'high' ? '#f97316' :
            ($hazard['risk_level'] === 'medium' ? '#eab308' : '#22c55e'))
        ); ?>;

        L.polygon(boundaryPoints.coordinates[0], {
            color: color,
            fillColor: color,
            fillOpacity: 0.5,
            weight: 2
        }).addTo(map)
        .bindPopup(`
            <div class="p-2">
                <h3 class="font-semibold">${<?php echo json_encode(htmlspecialchars($hazard['name'])); ?>}</h3>
                <p class="text-sm">Type: ${<?php echo json_encode(ucfirst($hazard['type'])); ?>}</p>
                <p class="text-sm">Risk Level: ${<?php echo json_encode(ucfirst($hazard['risk_level'])); ?>}</p>
            </div>
        `);
    <?php endforeach; ?>
});
</script>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 