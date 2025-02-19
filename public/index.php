<?php

/**
 * PAGASA Tropical Cyclone Monitoring System
 * Main Application Entry Point
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the application root path
define('APP_ROOT', dirname(__DIR__));

// Load Composer autoloader
require APP_ROOT . '/vendor/autoload.php';

// Load environment variables
try {
    $dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
    $dotenv->load();
    
    // Debug environment variables
    error_log("Environment variables loaded:");
    error_log("DB_HOST: " . $_ENV['DB_HOST'] ?? 'not set');
    error_log("DB_DATABASE: " . $_ENV['DB_DATABASE'] ?? 'not set');
    error_log("DB_USERNAME: " . $_ENV['DB_USERNAME'] ?? 'not set');
} catch (Exception $e) {
    error_log("Error loading .env file: " . $e->getMessage());
}

// Load application configuration
$config = require APP_ROOT . '/config/app.php';

// Initialize error handling
error_reporting(E_ALL);
ini_set('display_errors', $config['debug'] ? '1' : '0');

// Start session
session_start();

// Initialize router
$router = new App\Core\Router();

// Define routes
require APP_ROOT . '/routes/web.php';
require APP_ROOT . '/routes/api.php';

// Handle the request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Always show detailed error messages during development
    echo '<h1>Error</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<h2>Stack Trace:</h2>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
} 