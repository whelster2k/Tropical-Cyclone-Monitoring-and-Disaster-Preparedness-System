<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Middleware\Auth;

class AuthController extends ApiController {
    /**
     * Login user
     *
     * @return void
     */
    public function loginAction() {
        if (!$this->isPost()) {
            $this->error('Method not allowed', 405);
        }

        $data = $this->getJsonInput();
        $missing = $this->validateRequired($data, ['email', 'password']);
        $this->handleValidationErrors($missing);

        $user = User::authenticate($data['email'], $data['password']);

        if (!$user) {
            $this->error('Invalid credentials', 401);
        }

        // Generate JWT token
        $token = Auth::generateToken($user);

        $this->success([
            'user' => $user,
            'token' => $token
        ], 'Login successful');
    }

    /**
     * Register new user
     *
     * @return void
     */
    public function registerAction() {
        if (!$this->isPost()) {
            $this->error('Method not allowed', 405);
        }

        $data = $this->getJsonInput();
        $required = ['username', 'email', 'password', 'first_name', 'last_name'];
        $missing = $this->validateRequired($data, $required);
        $this->handleValidationErrors($missing);

        // Check if email already exists
        if (User::findByEmail($data['email'])) {
            $this->error('Email already registered', 422);
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format', 422);
        }

        // Validate password strength
        if (strlen($data['password']) < 8) {
            $this->error('Password must be at least 8 characters long', 422);
        }

        try {
            $user_id = User::register($data);
            $user = User::find($user_id);
            unset($user['password']);

            // Generate JWT token
            $token = Auth::generateToken($user);

            $this->success([
                'user' => $user,
                'token' => $token
            ], 'Registration successful', 201);

        } catch (\Exception $e) {
            $this->error('Registration failed', 500);
        }
    }

    /**
     * Refresh JWT token
     *
     * @return void
     */
    public function refreshAction() {
        if (!$this->isPost()) {
            $this->error('Method not allowed', 405);
        }

        $headers = getallheaders();
        $auth_header = $headers['Authorization'] ?? '';
        
        if (empty($auth_header) || !preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
            $this->error('No token provided', 401);
        }

        try {
            $jwt = $matches[1];
            $config = require APP_ROOT . '/config/app.php';
            $decoded = \Firebase\JWT\JWT::decode(
                $jwt, 
                new \Firebase\JWT\Key($config['jwt']['secret'], 'HS256')
            );
            
            $user = User::find($decoded->user_id);
            if (!$user) {
                $this->error('User not found', 404);
            }

            unset($user['password']);
            $token = Auth::generateToken($user);

            $this->success([
                'token' => $token
            ], 'Token refreshed');

        } catch (\Exception $e) {
            $this->error('Invalid token', 401);
        }
    }

    /**
     * Get current user profile
     *
     * @return void
     */
    public function profileAction() {
        if (!$this->isGet()) {
            $this->error('Method not allowed', 405);
        }

        $user_id = Auth::userId();
        if (!$user_id) {
            $this->error('Not authenticated', 401);
        }

        $user = User::find($user_id);
        if (!$user) {
            $this->error('User not found', 404);
        }

        unset($user['password']);
        $this->success(['user' => $user]);
    }

    /**
     * Update user profile
     *
     * @return void
     */
    public function updateProfileAction() {
        if (!$this->isPut()) {
            $this->error('Method not allowed', 405);
        }

        $user_id = Auth::userId();
        if (!$user_id) {
            $this->error('Not authenticated', 401);
        }

        $data = $this->getJsonInput();
        if (empty($data)) {
            $this->error('No data provided', 422);
        }

        // Remove protected fields
        unset($data['id'], $data['role'], $data['created_at'], $data['updated_at']);

        // Validate email if provided
        if (isset($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->error('Invalid email format', 422);
            }

            $existing = User::findByEmail($data['email']);
            if ($existing && $existing['id'] !== $user_id) {
                $this->error('Email already taken', 422);
            }
        }

        // Validate password if provided
        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) {
                $this->error('Password must be at least 8 characters long', 422);
            }
        }

        try {
            User::updateProfile($user_id, $data);
            $user = User::find($user_id);
            unset($user['password']);

            $this->success([
                'user' => $user
            ], 'Profile updated');

        } catch (\Exception $e) {
            $this->error('Update failed', 500);
        }
    }
} 