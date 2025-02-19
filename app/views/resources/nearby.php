<?php
$title = 'Nearby Emergency Resources';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nearby Emergency Resources</h1>
            <p class="text-gray-600 mt-2">Find emergency resources in your area</p>
        </div>
        <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-full animate-pulse">Live Updates</span>
    </div>

    <!-- Resource Availability Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Emergency Shelters -->
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Emergency Shelters</h2>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Available Capacity:</span>
                    <span class="font-semibold text-blue-600" data-summary="shelter">
                        <?php echo number_format($summaries['shelter']['total_capacity'] - $summaries['shelter']['current_occupancy']); ?> people
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php 
                        echo min(100, ($summaries['shelter']['current_occupancy'] / $summaries['shelter']['total_capacity']) * 100);
                    ?>%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span data-summary="shelter">Current: <?php echo number_format($summaries['shelter']['current_occupancy']); ?></span>
                    <span data-summary="shelter">Total: <?php echo number_format($summaries['shelter']['total_capacity']); ?></span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <?php echo $summaries['shelter']['available_shelters']; ?> of <?php echo $summaries['shelter']['total_shelters']; ?> shelters available
                </div>
            </div>
        </div>

        <!-- Medical Facilities -->
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Medical Facilities</h2>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Available Beds:</span>
                    <span class="font-semibold text-red-600" data-summary="medical">
                        <?php echo number_format($summaries['medical']['total_beds'] - $summaries['medical']['occupied_beds']); ?> beds
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-600 h-2 rounded-full" style="width: <?php 
                        echo min(100, ($summaries['medical']['occupied_beds'] / $summaries['medical']['total_beds']) * 100);
                    ?>%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span data-summary="medical">Occupied: <?php echo number_format($summaries['medical']['occupied_beds']); ?></span>
                    <span data-summary="medical">Total: <?php echo number_format($summaries['medical']['total_beds']); ?></span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <?php echo $summaries['medical']['available_facilities']; ?> of <?php echo $summaries['medical']['total_facilities']; ?> facilities accepting patients
                </div>
            </div>
        </div>

        <!-- Relief Centers -->
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Relief Centers</h2>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Supply Status:</span>
                    <span class="font-semibold text-yellow-600" data-summary="relief">
                        <?php 
                            $percentage = ($summaries['relief']['available_supplies'] / $summaries['relief']['total_capacity']) * 100;
                            echo $percentage > 66 ? 'High' : ($percentage > 33 ? 'Moderate' : 'Low');
                        ?>
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: <?php 
                        echo min(100, ($summaries['relief']['available_supplies'] / $summaries['relief']['total_capacity']) * 100);
                    ?>%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span data-summary="relief">Available: <?php echo number_format($summaries['relief']['available_supplies']); ?> kits</span>
                    <span data-summary="relief">Total: <?php echo number_format($summaries['relief']['total_capacity']); ?> kits</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <?php echo $summaries['relief']['centers_with_supplies']; ?> of <?php echo $summaries['relief']['total_centers']; ?> centers with supplies
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Resource Map -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="relative w-full" style="padding-bottom: 75%;">
                    <div id="resource-map" class="absolute inset-0">
                        <!-- Map will be initialized here -->
                    </div>
                </div>
                <div class="p-4 border-t">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Click on markers to view resource details
                        </div>
                        <button id="locate-me" class="flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Find My Location
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Legend -->
            <div class="bg-white rounded-lg shadow-lg p-4 mt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Resource Types</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Emergency Shelters</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Medical Facilities</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Relief Centers</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Available Resources</h2>
                    <div class="flex items-center">
                        <label for="resource-type" class="text-sm text-gray-600 mr-2">Filter:</label>
                        <select id="resource-type" class="text-sm border rounded px-2 py-1">
                            <option value="all">All Types</option>
                            <option value="shelter">Shelters</option>
                            <option value="medical">Medical</option>
                            <option value="relief">Relief</option>
                        </select>
                    </div>
                </div>
                
                <div id="resource-list" class="space-y-4">
                    <?php if (isset($resources) && !empty($resources)): ?>
                        <?php foreach ($resources as $resource): ?>
                            <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($resource['name']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($resource['address']); ?></p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-100 text-<?php echo $resource['status'] === 'active' ? 'green' : ($resource['status'] === 'full' ? 'yellow' : 'red'); ?>-800">
                                        <?php echo ucfirst($resource['status']); ?>
                                    </span>
                                </div>
                                <div class="mt-2 flex justify-between items-center">
                                    <?php if (isset($resource['distance'])): ?>
                                        <span class="text-sm text-gray-500">
                                            <?php echo number_format($resource['distance'], 1); ?> km away
                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-500">
                                            Distance unknown
                                        </span>
                                    <?php endif; ?>
                                    <a href="/pagasa/public/resources/<?php echo $resource['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Details →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">Click "Find My Location" to see nearby resources</p>
                            <button id="locate-me-alt" class="mt-4 flex items-center justify-center mx-auto text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Find My Location
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('resource-map').setView([8.0, 125.0], 7);
    let userMarker = null;
    let resourceMarkers = [];
    let currentPosition = null;

    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom marker icons
    const markerIcons = {
        shelter: L.divIcon({
            className: 'resource-marker shelter',
            html: '<div class="w-6 h-6 bg-blue-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">' +
                  '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>' +
                  '</svg></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        }),
        medical: L.divIcon({
            className: 'resource-marker medical',
            html: '<div class="w-6 h-6 bg-red-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">' +
                  '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>' +
                  '</svg></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        }),
        relief: L.divIcon({
            className: 'resource-marker relief',
            html: '<div class="w-6 h-6 bg-yellow-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">' +
                  '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>' +
                  '</svg></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    };

    // Function to calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the earth in km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2); 
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        return R * c; // Distance in km
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    // Function to get user's location
    document.getElementById('locate-me').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                currentPosition = { lat, lng };
                
                // Update map view
                map.setView([lat, lng], 12);

                // Add or update user marker
                if (userMarker) {
                    userMarker.setLatLng([lat, lng]);
                } else {
                    userMarker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'user-marker',
                            html: '<div class="w-6 h-6 bg-green-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">' +
                                  '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>' +
                                  '</svg></div>',
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        })
                    }).addTo(map);
                }

                // Load nearby resources
                loadResources(lat, lng);
            });
        }
    });

    // Function to update resource availability summaries
    function updateAvailabilitySummaries(resources) {
        let summaries = {
            shelter: { current: 0, total: 0 },
            medical: { occupied: 0, total: 0 },
            relief: { available: 0, total: 0 }
        };

        // Calculate totals from resources
        resources.forEach(resource => {
            if (resource.type === 'shelter') {
                summaries.shelter.current += resource.capacity - resource.availability.quantity;
                summaries.shelter.total += resource.capacity;
            } else if (resource.type === 'medical') {
                summaries.medical.occupied += resource.capacity - resource.availability.quantity;
                summaries.medical.total += resource.capacity;
            } else if (resource.type === 'relief') {
                summaries.relief.available += resource.availability.quantity;
                summaries.relief.total += resource.capacity;
            }
        });

        // Update shelter summary
        const shelterAvailable = summaries.shelter.total - summaries.shelter.current;
        const shelterPercentage = (summaries.shelter.current / summaries.shelter.total) * 100;
        document.querySelector('[data-summary="shelter"] .font-semibold').textContent = 
            `${shelterAvailable} people`;
        document.querySelector('[data-summary="shelter"] .bg-blue-600').style.width = 
            `${shelterPercentage}%`;
        document.querySelector('[data-summary="shelter"] .text-xs span:first-child').textContent = 
            `Current: ${summaries.shelter.current}`;
        document.querySelector('[data-summary="shelter"] .text-xs span:last-child').textContent = 
            `Total: ${summaries.shelter.total}`;

        // Update medical summary
        const bedsAvailable = summaries.medical.total - summaries.medical.occupied;
        const medicalPercentage = (summaries.medical.occupied / summaries.medical.total) * 100;
        document.querySelector('[data-summary="medical"] .font-semibold').textContent = 
            `${bedsAvailable} beds`;
        document.querySelector('[data-summary="medical"] .bg-red-600').style.width = 
            `${medicalPercentage}%`;
        document.querySelector('[data-summary="medical"] .text-xs span:first-child').textContent = 
            `Occupied: ${summaries.medical.occupied}`;
        document.querySelector('[data-summary="medical"] .text-xs span:last-child').textContent = 
            `Total: ${summaries.medical.total}`;

        // Update relief summary
        const reliefPercentage = (summaries.relief.available / summaries.relief.total) * 100;
        const reliefStatus = reliefPercentage > 66 ? 'High' : 
                           reliefPercentage > 33 ? 'Moderate' : 'Low';
        document.querySelector('[data-summary="relief"] .font-semibold').textContent = reliefStatus;
        document.querySelector('[data-summary="relief"] .bg-yellow-600').style.width = 
            `${reliefPercentage}%`;
        document.querySelector('[data-summary="relief"] .text-xs span:first-child').textContent = 
            `Available: ${summaries.relief.available} kits`;
        document.querySelector('[data-summary="relief"] .text-xs span:last-child').textContent = 
            `Total: ${summaries.relief.total} kits`;
    }

    // Function to update resource markers on map
    function updateResourceMarkers(resources) {
        // Clear existing markers
        resourceMarkers.forEach(marker => map.removeLayer(marker));
        resourceMarkers = [];

        // Add new markers
        resources.forEach(resource => {
            const marker = L.marker([resource.latitude, resource.longitude], {
                icon: markerIcons[resource.type]
            });

            // Calculate distance if user position is available
            let distanceText = '';
            if (currentPosition) {
                const distance = calculateDistance(
                    currentPosition.lat,
                    currentPosition.lng,
                    resource.latitude,
                    resource.longitude
                );
                distanceText = `<p class="mt-2"><strong>Distance:</strong> ${distance.toFixed(1)} km</p>`;
            }

            marker.bindPopup(`
                <div class="text-sm">
                    <h3 class="font-semibold">${resource.name}</h3>
                    <p class="text-gray-600">${resource.description || ''}</p>
                    <p class="mt-2">
                        <strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full bg-${getStatusColor(resource.status)}-100 text-${getStatusColor(resource.status)}-800">
                            ${resource.status}
                        </span>
                    </p>
                    ${distanceText}
                    <p class="mt-2">
                        <strong>Contact:</strong> ${resource.contact_number}
                    </p>
                    <div class="mt-3">
                        <a href="/pagasa/public/resources/${resource.id}" 
                           class="inline-block bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            `);

            marker.addTo(map);
            resourceMarkers.push(marker);
        });
    }

    // Function to update resource list
    function updateResourceList(resources) {
        const container = document.getElementById('resource-list');
        
        if (!resources || resources.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500">No resources found in this area</p>
                </div>
            `;
            return;
        }

        container.innerHTML = resources.map(resource => `
            <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800">${resource.name}</h3>
                        <p class="text-sm text-gray-600">${resource.address}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-${getStatusColor(resource.status)}-100 text-${getStatusColor(resource.status)}-800">
                        ${resource.status}
                    </span>
                </div>
                <div class="mt-2 flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        ${calculateDistance(resource.distance)} km away
                    </span>
                    <a href="/pagasa/public/resources/${resource.id}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Details →
                    </a>
                </div>
            </div>
        `).join('');
    }

    // Helper function to get marker color based on type
    function getMarkerColor(type) {
        switch (type) {
            case 'shelter': return 'blue';
            case 'medical': return 'red';
            case 'relief': return 'yellow';
            default: return 'gray';
        }
    }

    // Helper function to get status color
    function getStatusColor(status) {
        switch (status) {
            case 'active': return 'green';
            case 'full': return 'yellow';
            case 'inactive': return 'red';
            default: return 'gray';
        }
    }

    // Helper function to format distance
    function calculateDistance(distance) {
        return parseFloat(distance).toFixed(1);
    }

    // Filter resources by type
    document.getElementById('resource-type').addEventListener('change', function(e) {
        if (userMarker) {
            const position = userMarker.getLatLng();
            loadResources(position.lat, position.lng, e.target.value);
        }
    });

    // Initial load if location is in URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const lat = urlParams.get('lat');
    const lng = urlParams.get('lng');
    const type = urlParams.get('type');
    
    if (lat && lng) {
        map.setView([lat, lng], 12);
        loadResources(lat, lng, type || 'all');
    }

    // Update the loadResources function to include availability updates
    function loadResources(lat, lng, type = 'all') {
        const radius = 50; // 50km radius
        fetch(`/pagasa/public/api/resources/nearby?lat=${lat}&lng=${lng}&radius=${radius}&type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateResourceMarkers(data.data.resources);
                    updateResourceList(data.data.resources);
                    updateAvailabilitySummaries(data.data.resources);
                }
            })
            .catch(error => console.error('Error loading resources:', error));
    }

    // Add event listener for the alternative locate button
    document.getElementById('locate-me-alt')?.addEventListener('click', function() {
        document.getElementById('locate-me').click();
    });
});
</script>

<style>
.user-marker {
    z-index: 1000;
}

.resource-marker {
    z-index: 900;
}

.resource-marker.shelter div {
    background-color: #3B82F6;
}

.resource-marker.medical div {
    background-color: #EF4444;
}

.resource-marker.relief div {
    background-color: #F59E0B;
}
</style>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 