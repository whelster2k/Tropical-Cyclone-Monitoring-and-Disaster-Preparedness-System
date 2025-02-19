<?php

namespace App\Models;

use App\Core\Model;

class Resource extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'resources';

    /**
     * Get all active resources with their current availability
     *
     * @return array
     */
    public static function getActive() {
        $sql = "SELECT r.*, 
                ra.quantity as available_quantity,
                ra.status as availability_status,
                ra.last_update as availability_update
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.status = 'active'
                ORDER BY r.name";
        
        return static::raw($sql);
    }

    /**
     * Get resources near a location with accurate distance calculation
     * and current availability status
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @param float $radius Radius in kilometers
     * @param string $type Optional resource type filter
     * @return array
     */
    public static function getNearby($lat, $lng, $radius, $type = null) {
        // Haversine formula for accurate distance calculation
        $sql = "SELECT r.*,
                ra.quantity as available_quantity,
                ra.status as availability_status,
                ra.last_update as availability_update,
                (
                    6371 * acos(
                        cos(radians(:lat)) * 
                        cos(radians(latitude)) * 
                        cos(radians(longitude) - radians(:lng)) + 
                        sin(radians(:lat)) * 
                        sin(radians(latitude))
                    )
                ) AS distance
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.status = 'active'";

        $params = ['lat' => $lat, 'lng' => $lng, 'radius' => $radius];
        
        if ($type && $type !== 'all') {
            $sql .= " AND r.type = :type";
            $params['type'] = $type;
        }

        $sql .= " HAVING distance < :radius
                ORDER BY distance ASC";

        return static::raw($sql, $params);
    }

    /**
     * Get resources by type with availability information
     *
     * @param string $type
     * @return array
     */
    public static function getByType($type) {
        $sql = "SELECT r.*, 
                ra.quantity as available_quantity,
                ra.status as availability_status,
                ra.last_update as availability_update
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.type = :type AND r.status = 'active'
                ORDER BY r.name";
        
        return static::raw($sql, ['type' => $type]);
    }

    /**
     * Get detailed resource availability
     *
     * @param int $id
     * @return array|false
     */
    public static function getAvailability($id) {
        $sql = "SELECT r.*, 
                ra.quantity as available_quantity,
                ra.status as availability_status,
                ra.last_update as availability_update,
                CASE 
                    WHEN r.type = 'shelter' THEN r.capacity - COALESCE(ra.quantity, 0)
                    WHEN r.type = 'medical' THEN r.capacity - COALESCE(ra.quantity, 0)
                    ELSE COALESCE(ra.quantity, 0)
                END as current_usage
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.id = :id";
        
        $result = static::raw($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Update resource availability
     *
     * @param int $id
     * @param int $quantity
     * @param string $status
     * @return bool
     */
    public static function updateAvailability($id, $quantity, $status = null) {
        $resource = self::getAvailability($id);
        if (!$resource) return false;

        // Determine status if not provided
        if (!$status) {
            $capacity = $resource['capacity'];
            if ($resource['type'] === 'relief') {
                $status = $quantity > ($capacity * 0.66) ? 'available' :
                         ($quantity > ($capacity * 0.33) ? 'limited' : 'unavailable');
            } else {
                $available = $capacity - $quantity;
                $status = $available > ($capacity * 0.33) ? 'available' :
                         ($available > ($capacity * 0.1) ? 'limited' : 'unavailable');
            }
        }

        $sql = "INSERT INTO resource_availability (resource_id, quantity, status, last_update)
                VALUES (:id, :quantity, :status, NOW())
                ON DUPLICATE KEY UPDATE
                quantity = VALUES(quantity),
                status = VALUES(status),
                last_update = VALUES(last_update)";

        return static::raw($sql, [
            'id' => $id,
            'quantity' => $quantity,
            'status' => $status
        ]);
    }

    /**
     * Get summary of resource availability by type
     *
     * @return array
     */
    public static function getAvailabilitySummary() {
        $sql = "SELECT 
                r.type,
                COUNT(r.id) as total_facilities,
                SUM(r.capacity) as total_capacity,
                SUM(CASE 
                    WHEN r.type IN ('shelter', 'medical') THEN r.capacity - COALESCE(ra.quantity, 0)
                    ELSE COALESCE(ra.quantity, 0)
                END) as current_usage
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.status = 'active'
                GROUP BY r.type";
        
        return static::raw($sql);
    }

    /**
     * Get detailed summary for emergency shelters
     *
     * @return array
     */
    public static function getShelterSummary() {
        $sql = "SELECT 
                SUM(r.capacity) as total_capacity,
                SUM(COALESCE(ra.quantity, 0)) as current_occupancy,
                COUNT(r.id) as total_shelters,
                SUM(CASE 
                    WHEN ra.status = 'available' THEN 1 
                    ELSE 0 
                END) as available_shelters
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.type = 'shelter' AND r.status = 'active'";
        
        $result = static::raw($sql);
        return $result ? $result[0] : [
            'total_capacity' => 0,
            'current_occupancy' => 0,
            'total_shelters' => 0,
            'available_shelters' => 0
        ];
    }

    /**
     * Get detailed summary for medical facilities
     *
     * @return array
     */
    public static function getMedicalSummary() {
        $sql = "SELECT 
                SUM(r.capacity) as total_beds,
                SUM(COALESCE(ra.quantity, 0)) as occupied_beds,
                COUNT(r.id) as total_facilities,
                SUM(CASE 
                    WHEN ra.status = 'available' THEN 1 
                    ELSE 0 
                END) as available_facilities
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.type = 'medical' AND r.status = 'active'";
        
        $result = static::raw($sql);
        return $result ? $result[0] : [
            'total_beds' => 0,
            'occupied_beds' => 0,
            'total_facilities' => 0,
            'available_facilities' => 0
        ];
    }

    /**
     * Get detailed summary for relief centers
     *
     * @return array
     */
    public static function getReliefSummary() {
        $sql = "SELECT 
                SUM(r.capacity) as total_capacity,
                SUM(COALESCE(ra.quantity, 0)) as available_supplies,
                COUNT(r.id) as total_centers,
                SUM(CASE 
                    WHEN ra.status = 'available' THEN 1 
                    ELSE 0 
                END) as centers_with_supplies
                FROM resources r
                LEFT JOIN resource_availability ra ON r.id = ra.resource_id
                WHERE r.type = 'relief' AND r.status = 'active'";
        
        $result = static::raw($sql);
        return $result ? $result[0] : [
            'total_capacity' => 0,
            'available_supplies' => 0,
            'total_centers' => 0,
            'centers_with_supplies' => 0
        ];
    }

    /**
     * Get comprehensive summary of all resource types
     *
     * @return array
     */
    public static function getComprehensiveSummary() {
        return [
            'shelter' => self::getShelterSummary(),
            'medical' => self::getMedicalSummary(),
            'relief' => self::getReliefSummary()
        ];
    }
} 