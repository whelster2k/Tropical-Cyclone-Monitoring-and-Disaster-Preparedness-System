<?php

/**
 * API Routes
 * All routes here are prefixed with /api
 */

use App\Core\Router;

// Authentication endpoints
$router->add('/api/auth/login', [
    'controller' => 'Api\Auth',
    'action' => 'login'
], 'POST');

$router->add('/api/auth/register', [
    'controller' => 'Api\Auth',
    'action' => 'register'
], 'POST');

$router->add('/api/auth/refresh', [
    'controller' => 'Api\Auth',
    'action' => 'refresh'
], 'POST');

// User profile endpoints
$router->add('/api/profile', [
    'controller' => 'Api\Profile',
    'action' => 'show'
], 'GET');

$router->add('/api/profile', [
    'controller' => 'Api\Profile',
    'action' => 'update'
], 'PUT');

$router->add('/api/profile/location', [
    'controller' => 'Api\Profile',
    'action' => 'updateLocation'
], 'PUT');

// Cyclone endpoints
$router->add('/api/cyclones', [
    'controller' => 'Api\Cyclone',
    'action' => 'index'
], 'GET');

$router->add('/api/cyclones/{id:\d+}', [
    'controller' => 'Api\Cyclone',
    'action' => 'show'
], 'GET');

$router->add('/api/cyclones/active', [
    'controller' => 'Api\Cyclone',
    'action' => 'active'
], 'GET');

$router->add('/api/cyclones/historical', [
    'controller' => 'Api\Cyclone',
    'action' => 'historical'
], 'GET');

$router->add('/api/cyclones/{id:\d+}/track', [
    'controller' => 'Api\Cyclone',
    'action' => 'track'
], 'GET');

// Hazard assessment endpoints
$router->add('/api/hazards', [
    'controller' => 'Api\Hazard',
    'action' => 'index'
], 'GET');

$router->add('/api/hazards/{id:\d+}', [
    'controller' => 'Api\Hazard',
    'action' => 'show'
], 'GET');

$router->add('/api/hazards/assess', [
    'controller' => 'Api\Hazard',
    'action' => 'assess'
], 'POST');

$router->add('/api/hazards/nearby', [
    'controller' => 'Api\Hazard',
    'action' => 'nearby'
], 'GET');

// Alert endpoints
$router->add('/api/alerts', [
    'controller' => 'Api\Alert',
    'action' => 'index'
], 'GET');

$router->add('/api/alerts/active', [
    'controller' => 'Api\Alert',
    'action' => 'active'
], 'GET');

$router->add('/api/alerts/{id:\d+}', [
    'controller' => 'Api\Alert',
    'action' => 'show'
], 'GET');

$router->add('/api/alerts/subscribe', [
    'controller' => 'Api\Alert',
    'action' => 'subscribe'
], 'POST');

$router->add('/api/alerts/unsubscribe', [
    'controller' => 'Api\Alert',
    'action' => 'unsubscribe'
], 'POST');

// Community report endpoints
$router->add('/api/reports', [
    'controller' => 'Api\Report',
    'action' => 'index'
], 'GET');

$router->add('/api/reports', [
    'controller' => 'Api\Report',
    'action' => 'create'
], 'POST');

$router->add('/api/reports/{id:\d+}', [
    'controller' => 'Api\Report',
    'action' => 'show'
], 'GET');

$router->add('/api/reports/{id:\d+}', [
    'controller' => 'Api\Report',
    'action' => 'update'
], 'PUT');

$router->add('/api/reports/{id:\d+}', [
    'controller' => 'Api\Report',
    'action' => 'delete'
], 'DELETE');

$router->add('/api/reports/nearby', [
    'controller' => 'Api\Report',
    'action' => 'nearby'
], 'GET');

// Emergency resource endpoints
$router->add('/api/resources', [
    'controller' => 'ResourceController',
    'action' => 'index'
], 'GET');

$router->add('/api/resources/{id:\d+}', [
    'controller' => 'ResourceController',
    'action' => 'show'
], 'GET');

$router->add('/api/resources/nearby', [
    'controller' => 'ResourceController',
    'action' => 'nearby'
], 'GET');

$router->add('/api/resources/{id:\d+}/availability', [
    'controller' => 'ResourceController',
    'action' => 'updateAvailability'
], 'POST');

// Statistics endpoints
$router->add('/api/stats/cyclones', [
    'controller' => 'Api\Stats',
    'action' => 'cyclones'
], 'GET');

$router->add('/api/stats/hazards', [
    'controller' => 'Api\Stats',
    'action' => 'hazards'
], 'GET');

$router->add('/api/stats/alerts', [
    'controller' => 'Api\Stats',
    'action' => 'alerts'
], 'GET');

$router->add('/api/stats/reports', [
    'controller' => 'Api\Stats',
    'action' => 'reports'
], 'GET');

$router->add('/api/stats/resources', [
    'controller' => 'Api\Stats',
    'action' => 'resources'
], 'GET');

// Admin endpoints
$router->add('/api/admin/users', [
    'controller' => 'Api\Admin',
    'action' => 'users'
], 'GET');

$router->add('/api/admin/cyclones', [
    'controller' => 'Api\Admin',
    'action' => 'cyclones'
], 'GET');

$router->add('/api/admin/hazards', [
    'controller' => 'Api\Admin',
    'action' => 'hazards'
], 'GET');

$router->add('/api/admin/alerts', [
    'controller' => 'Api\Admin',
    'action' => 'alerts'
], 'GET');

$router->add('/api/admin/reports', [
    'controller' => 'Api\Admin',
    'action' => 'reports'
], 'GET');

$router->add('/api/admin/resources', [
    'controller' => 'Api\Admin',
    'action' => 'resources'
], 'GET');

// Hazard API Routes
$router->add('/api/hazards/weather', [
    'controller' => 'HazardController',
    'action' => 'weather'
], 'GET');

$router->add('/api/hazards/auto-update', [
    'controller' => 'Api\Hazard',
    'action' => 'getAutoUpdate'
], 'GET');

$router->add('/api/hazards/updates', [
    'controller' => 'HazardController',
    'action' => 'updates'
], 'GET');

$router->add('/api/hazards/alerts', [
    'controller' => 'HazardController',
    'action' => 'alerts'
], 'GET');

$router->add('/api/hazards/update-risk', [
    'controller' => 'HazardController',
    'action' => 'updateRisk'
], 'POST');

$router->add('/api/hazards/calculate-risk', [
    'controller' => 'HazardController',
    'action' => 'calculateRisk'
], 'GET');

$router->add('/api/hazards', [
    'controller' => 'Api\Hazard',
    'action' => 'create'
], 'POST');

$router->add('/api/hazards/{id:\d+}', [
    'controller' => 'Api\Hazard',
    'action' => 'delete'
], 'DELETE');

// Add JWT middleware to protected API routes
$protected_api_routes = [
    '/api/profile',
    '/api/profile/*',
    '/api/reports/create',
    '/api/reports/*/update',
    '/api/reports/*/delete',
    '/api/alerts/subscribe',
    '/api/alerts/unsubscribe'
];

foreach ($protected_api_routes as $route) {
    $router->middleware($route, 'App\Middleware\JwtAuth');
}

// Add admin middleware to admin API routes
$admin_api_routes = [
    '/api/admin/*'
];

foreach ($admin_api_routes as $route) {
    $router->middleware($route, 'App\Middleware\AdminApi');
} 