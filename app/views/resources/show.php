<?php
$title = 'Resource Details';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Resource Header -->
        <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg overflow-hidden mb-6 transition-colors">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($resource['name']); ?></h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2"><?php echo htmlspecialchars($resource['description']); ?></p>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <span class="px-3 py-1 text-sm rounded-full bg-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-100 dark:bg-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-900 text-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-800 dark:text-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-200">
                            <?php echo ucfirst($resource['status']); ?>
                        </span>
                        <?php if (isset($resource['distance'])): ?>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo number_format($resource['distance'], 1); ?> km away
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Resource Information -->
            <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg p-6 transition-colors">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Resource Information</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 mr-3 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Type</label>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                <?php echo ucfirst($resource['type']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 mr-3 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Address</label>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                <?php echo htmlspecialchars($resource['address']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 mr-3 flex items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Contact Number</label>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                <?php echo htmlspecialchars($resource['contact_number']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 mr-3 flex items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Total Capacity</label>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                <?php echo number_format($resource['capacity']); ?>
                                <?php echo $resource['type'] === 'shelter' ? 'people' : ($resource['type'] === 'medical' ? 'beds' : 'supplies'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability Status -->
            <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg p-6 transition-colors">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Current Availability</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-400">
                            <?php 
                            switch($resource['type']) {
                                case 'shelter':
                                    echo 'Available Space';
                                    break;
                                case 'medical':
                                    echo 'Available Beds';
                                    break;
                                case 'relief':
                                    echo 'Available Supplies';
                                    break;
                            }
                            ?>
                        </label>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-1">
                            <?php 
                            $available = $resource['type'] === 'relief' 
                                ? $resource['available_quantity']
                                : ($resource['capacity'] - $resource['current_usage']);
                            echo number_format($available);
                            echo $resource['type'] === 'shelter' ? ' people' : ($resource['type'] === 'medical' ? ' beds' : ' kits');
                            ?>
                        </p>
                    </div>
                    
                    <!-- Capacity Progress Bar -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span>Capacity Usage</span>
                            <span>
                                <?php
                                $percentage = $resource['type'] === 'relief'
                                    ? ($resource['available_quantity'] / $resource['capacity']) * 100
                                    : ($resource['current_usage'] / $resource['capacity']) * 100;
                                echo number_format($percentage, 1) . '%';
                                ?>
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div class="h-4 rounded-full <?php 
                                echo $percentage > 80 ? 'bg-red-600' : 
                                     ($percentage > 50 ? 'bg-yellow-600' : 'bg-green-600');
                            ?> transition-all duration-300" style="width: <?php echo min(100, $percentage); ?>%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Last Updated</label>
                        <p class="font-medium text-gray-800 dark:text-gray-200 mt-1">
                            <?php echo date('F j, Y g:i A', strtotime($resource['availability_update'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Map -->
        <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg overflow-hidden mt-6 transition-colors">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Location</h2>
                <div class="relative w-full rounded-lg overflow-hidden" style="padding-bottom: 50%;">
                    <div id="resource-map" class="absolute inset-0"></div>
                </div>
                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    <p>Click on the marker to view the address</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="/pagasa/public/resources/nearby" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Resources
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('resource-map').setView([<?php echo $resource['latitude']; ?>, <?php echo $resource['longitude']; ?>], 15);

    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom icon based on resource type
    const icon = L.divIcon({
        className: 'resource-marker <?php echo $resource['type']; ?>',
        html: `<div class="w-8 h-8 flex items-center justify-center bg-<?php 
            echo $resource['type'] === 'shelter' ? 'blue' : 
                 ($resource['type'] === 'medical' ? 'red' : 'yellow'); 
        ?>-500 dark:bg-<?php 
            echo $resource['type'] === 'shelter' ? 'blue' : 
                 ($resource['type'] === 'medical' ? 'red' : 'yellow'); 
        ?>-600 rounded-full border-2 border-white shadow-lg">
            <?php if ($resource['type'] === 'shelter'): ?>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            <?php elseif ($resource['type'] === 'medical'): ?>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            <?php else: ?>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            <?php endif; ?>
        </div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 16]
    });

    // Add marker with custom icon
    const marker = L.marker([<?php echo $resource['latitude']; ?>, <?php echo $resource['longitude']; ?>], {
        icon: icon
    }).addTo(map);

    // Add popup with enhanced info
    marker.bindPopup(`
        <div class="text-sm">
            <h3 class="font-semibold"><?php echo htmlspecialchars($resource['name']); ?></h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($resource['address']); ?></p>
            <p class="mt-2">
                <strong>Status:</strong> 
                <span class="px-2 py-1 text-xs rounded-full bg-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-100 text-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-800">
                    <?php echo ucfirst($resource['status']); ?>
                </span>
            </p>
            <p class="mt-2">
                <strong>Contact:</strong> <?php echo htmlspecialchars($resource['contact_number']); ?>
            </p>
        </div>
    `);
});
</script>

<style>
.resource-marker {
    z-index: 1000;
}

.leaflet-popup-content-wrapper {
    border-radius: 0.5rem;
}

.leaflet-popup-content {
    margin: 0.75rem;
}

/* Dark mode styles for map */
.dark .leaflet-popup-content-wrapper {
    background-color: #2d2d2d;
    color: #e5e5e5;
}

.dark .leaflet-popup-tip {
    background-color: #2d2d2d;
}

.dark .leaflet-popup-content p {
    color: #9ca3af;
}

.dark .leaflet-popup-content h3 {
    color: #e5e5e5;
}
</style>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 