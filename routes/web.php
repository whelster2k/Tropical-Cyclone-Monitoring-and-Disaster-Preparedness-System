<?php

/**
 * Web Routes
 */

// Home routes
$router->add('/', [
    'controller' => 'Home',
    'action' => 'index'
]);

// Authentication routes
$router->add('/login', [
    'controller' => 'Auth',
    'action' => 'login'
]);

$router->add('/register', [
    'controller' => 'Auth',
    'action' => 'register'
]);

$router->add('/logout', [
    'controller' => 'Auth',
    'action' => 'logout'
]);

// User profile routes
$router->add('/profile', [
    'controller' => 'Profile',
    'action' => 'index'
]);

$router->add('/profile/edit', [
    'controller' => 'Profile',
    'action' => 'edit'
]);

$router->add('/profile/update-location', [
    'controller' => 'Profile',
    'action' => 'updateLocation'
]);

// Cyclone monitoring routes
$router->add('/cyclones', [
    'controller' => 'CycloneController',
    'action' => 'index'
]);

$router->add('/cyclones/{id:\d+}', [
    'controller' => 'CycloneController',
    'action' => 'show'
]);

$router->add('/cyclones/track', [
    'controller' => 'CycloneController',
    'action' => 'track'
]);

$router->add('/cyclones/historical', [
    'controller' => 'CycloneController',
    'action' => 'historical'
]);

// Hazard assessment routes
$router->add('/hazards', [
    'controller' => 'HazardController',
    'action' => 'index'
]);

$router->add('/hazards/map', [
    'controller' => 'HazardController',
    'action' => 'map'
]);

$router->add('/hazards/assess', [
    'controller' => 'HazardController',
    'action' => 'assess'
]);

// Alert routes
$router->add('/alerts', [
    'controller' => 'Alert',
    'action' => 'index'
]);

$router->add('/alerts/{id:\d+}', [
    'controller' => 'Alert',
    'action' => 'show'
]);

$router->add('/alerts/subscribe', [
    'controller' => 'Alert',
    'action' => 'subscribe'
]);

// Community reports routes
$router->add('/reports', [
    'controller' => 'Report',
    'action' => 'index'
]);

$router->add('/reports/create', [
    'controller' => 'Report',
    'action' => 'create'
]);

$router->add('/reports/{id:\d+}', [
    'controller' => 'Report',
    'action' => 'show'
]);

$router->add('/reports/{id:\d+}/edit', [
    'controller' => 'Report',
    'action' => 'edit'
]);

// Resource routes
$router->add('/resources', [
    'controller' => 'ResourceController',
    'action' => 'index'
]);

$router->add('/resources/nearby', [
    'controller' => 'ResourceController',
    'action' => 'nearby'
]);

$router->add('/resources/{id:\d+}', [
    'controller' => 'ResourceController',
    'action' => 'show'
]);

// Admin routes
$router->add('/admin', [
    'controller' => 'Admin',
    'action' => 'index'
]);

$router->add('/admin/users', [
    'controller' => 'Admin',
    'action' => 'users'
]);

$router->add('/admin/cyclones', [
    'controller' => 'Admin',
    'action' => 'cyclones'
]);

$router->add('/admin/hazards', [
    'controller' => 'Admin',
    'action' => 'hazards'
]);

$router->add('/admin/alerts', [
    'controller' => 'Admin',
    'action' => 'alerts'
]);

$router->add('/admin/reports', [
    'controller' => 'Admin',
    'action' => 'reports'
]);

$router->add('/admin/resources', [
    'controller' => 'Admin',
    'action' => 'resources'
]);

// Add middleware to protected routes
$protected_routes = [
    '/profile',
    '/profile/edit',
    '/profile/update-location',
    '/reports/create',
    '/reports/*/edit',
    '/alerts/subscribe'
];

foreach ($protected_routes as $route) {
    $router->middleware($route, 'App\Middleware\Auth');
}

// Add admin middleware to admin routes
$admin_routes = [
    '/admin',
    '/admin/*'
];

foreach ($admin_routes as $route) {
    $router->middleware($route, 'App\Middleware\Admin');
} 