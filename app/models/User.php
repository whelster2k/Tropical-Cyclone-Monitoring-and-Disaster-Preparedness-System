<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'users';

    /**
     * Find a user by email
     *
     * @param string $email
     * @return array|false
     */
    public static function findByEmail($email) {
        return static::where(['email' => $email])[0] ?? false;
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return int
     */
    public static function register($data) {
        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }
        
        return static::create($data);
    }

    /**
     * Attempt to authenticate a user
     *
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public static function authenticate($email, $password) {
        $user = static::findByEmail($email);
        
        if ($user && $password === $user['password']) {
            unset($user['password']); // Don't include password in returned data
            return $user;
        }
        
        return false;
    }

    /**
     * Update user's profile
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateProfile($id, $data) {
        return static::update($id, $data);
    }

    /**
     * Get user's location
     *
     * @param int $id
     * @return array|null
     */
    public static function getLocation($id) {
        $user = static::find($id);
        
        if ($user && isset($user['location_lat']) && isset($user['location_lng'])) {
            return [
                'lat' => $user['location_lat'],
                'lng' => $user['location_lng']
            ];
        }
        
        return null;
    }

    /**
     * Update user's location
     *
     * @param int $id
     * @param float $lat
     * @param float $lng
     * @return bool
     */
    public static function updateLocation($id, $lat, $lng) {
        return static::update($id, [
            'location_lat' => $lat,
            'location_lng' => $lng
        ]);
    }

    /**
     * Get users in a specific area
     *
     * @param float $lat
     * @param float $lng
     * @param float $radius in kilometers
     * @return array
     */
    public static function getUsersInArea($lat, $lng, $radius) {
        // Haversine formula to calculate distance
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) 
                * cos(radians(location_lat)) 
                * cos(radians(location_lng) - radians(:lng)) 
                + sin(radians(:lat)) 
                * sin(radians(location_lat)))) AS distance 
                FROM users 
                HAVING distance < :radius 
                ORDER BY distance";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ]);
    }

    /**
     * Get all admin users
     *
     * @return array
     */
    public static function getAdmins() {
        return static::where(['role' => 'admin']);
    }

    /**
     * Check if user exists
     *
     * @param int $id
     * @return bool
     */
    public static function exists($id) {
        return static::find($id) !== false;
    }

    /**
     * Get user's full name
     *
     * @param array $user
     * @return string
     */
    public static function getFullName($user) {
        return trim($user['first_name'] . ' ' . $user['last_name']);
    }
} 