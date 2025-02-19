<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    /**
     * Handle the authentication check
     *
     * @return bool
     */
    public function handle() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Check for JWT token in Authorization header
            $headers = getallheaders();
            $auth_header = $headers['Authorization'] ?? '';
            
            if (empty($auth_header) || !preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(['error' => 'Authentication required']);
                return false;
            }

            try {
                $jwt = $matches[1];
                $config = require APP_ROOT . '/config/app.php';
                $decoded = JWT::decode($jwt, new Key($config['jwt']['secret'], 'HS256'));
                
                // Set user session from JWT
                $_SESSION['user_id'] = $decoded->user_id;
                $_SESSION['user_role'] = $decoded->role;
                
            } catch (\Exception $e) {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(['error' => 'Invalid token']);
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a new JWT token
     *
     * @param array $user User data
     *
     * @return string
     */
    public static function generateToken($user) {
        $config = require APP_ROOT . '/config/app.php';
        $jwt_config = $config['jwt'];

        $payload = [
            'user_id' => $user['id'],
            'role' => $user['role'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + $jwt_config['expiration']
        ];

        return JWT::encode($payload, $jwt_config['secret'], 'HS256');
    }

    /**
     * Check if user has required role
     *
     * @param string|array $roles Required role(s)
     *
     * @return bool
     */
    public static function hasRole($roles) {
        if (!isset($_SESSION['user_role'])) {
            return false;
        }

        if (is_string($roles)) {
            return $_SESSION['user_role'] === $roles;
        }

        return in_array($_SESSION['user_role'], $roles);
    }

    /**
     * Get current user ID
     *
     * @return int|null
     */
    public static function userId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user role
     *
     * @return string|null
     */
    public static function userRole() {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Logout current user
     *
     * @return void
     */
    public static function logout() {
        session_destroy();
        session_start();
    }
} 