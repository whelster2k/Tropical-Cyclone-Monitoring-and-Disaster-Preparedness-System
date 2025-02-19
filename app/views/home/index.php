<?php
$title = 'Welcome to PAGASA';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-xl overflow-hidden mb-12">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-20">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">PAGASA Cyclone Monitor</h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8">Real-time tropical cyclone monitoring and hazard assessment system</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="/pagasa/public/cyclones" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-semibold transition-colors">
                        View Active Cyclones
                    </a>
                    <a href="/pagasa/public/resources/nearby" class="bg-blue-500 hover:bg-blue-400 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Find Emergency Resources
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Features -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Active Cyclones -->
        <div class="bg-white dark:bg-dark-card rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-xl">
            <div class="p-6">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">Active Cyclones</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Monitor active tropical cyclones in the region.</p>
                <a href="/pagasa/public/cyclones" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium inline-flex items-center transition-colors">
                    View Cyclones
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Hazard Assessment -->
        <div class="bg-white dark:bg-dark-card rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-xl">
            <div class="p-6">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">Hazard Assessment</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Check hazard zones and risk levels in your area.</p>
                <a href="/pagasa/public/hazards" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium inline-flex items-center transition-colors">
                    View Hazards
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Emergency Resources -->
        <div class="bg-white dark:bg-dark-card rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-xl">
            <div class="p-6">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">Emergency Resources</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Find nearby emergency shelters and resources.</p>
                <a href="/pagasa/public/resources" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium inline-flex items-center transition-colors">
                    View Resources
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Latest Updates Section -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-lg overflow-hidden mb-12">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Latest Updates</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <span class="inline-block p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Weather Update</h3>
                        <p class="text-gray-600 dark:text-gray-400">Current weather conditions in Mindanao region are stable. No immediate weather disturbances detected.</p>
                        <span class="text-sm text-gray-500 dark:text-gray-500">Updated 5 minutes ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/pagasa/public/alerts" class="bg-white dark:bg-dark-card p-4 rounded-lg shadow hover:shadow-md transition-shadow text-center">
            <span class="text-gray-800 dark:text-gray-100 font-medium">Weather Alerts</span>
        </a>
        <a href="/pagasa/public/reports" class="bg-white dark:bg-dark-card p-4 rounded-lg shadow hover:shadow-md transition-shadow text-center">
            <span class="text-gray-800 dark:text-gray-100 font-medium">Community Reports</span>
        </a>
        <a href="/pagasa/public/hazards/map" class="bg-white dark:bg-dark-card p-4 rounded-lg shadow hover:shadow-md transition-shadow text-center">
            <span class="text-gray-800 dark:text-gray-100 font-medium">Hazard Map</span>
        </a>
        <a href="/pagasa/public/resources/nearby" class="bg-white dark:bg-dark-card p-4 rounded-lg shadow hover:shadow-md transition-shadow text-center">
            <span class="text-gray-800 dark:text-gray-100 font-medium">Nearby Resources</span>
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 