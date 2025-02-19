<?php

namespace App\Models;

use App\Core\Model;

class EmergencyResource extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'emergency_resources';

    /**
     * Get available resources
     *
     * @param string $type Optional resource type filter
     * @return array
     */
    public static function getAvailable($type = null) {
        $conditions = ['status' => 'available'];
        
        if ($type) {
            $conditions['type'] = $type;
        }
        
        return static::where($conditions);
    }

    /**
     * Get resources by type
     *
     * @param string $type
     * @return array
     */
    public static function getByType($type) {
        return static::where(['type' => $type]);
    }

    /**
     * Get resources in area
     *
     * @param float $lat
     * @param float $lng
     * @param float $radius in kilometers
     * @param string $type Optional resource type
     * @param string $status Optional status filter
     * @return array
     */
    public static function getInArea($lat, $lng, $radius, $type = null, $status = null) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) 
                * cos(radians(lat)) 
                * cos(radians(lng) - radians(:lng)) 
                + sin(radians(:lat)) 
                * sin(radians(lat)))) AS distance 
                FROM emergency_resources
                WHERE 1=1";
        
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ];
        
        if ($type) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " HAVING distance < :radius 
                  ORDER BY distance";
        
        return static::raw($sql, $params);
    }

    /**
     * Update resource capacity
     *
     * @param int $id
     * @param int $capacity
     * @return bool
     */
    public static function updateCapacity($id, $capacity) {
        $resource = static::find($id);
        if (!$resource) {
            return false;
        }
        
        $status = 'available';
        if ($capacity <= 0) {
            $status = 'full';
        }
        
        return static::update($id, [
            'capacity' => $capacity,
            'status' => $status
        ]);
    }

    /**
     * Update resource status
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateStatus($id, $status) {
        return static::update($id, ['status' => $status]);
    }

    /**
     * Get nearest resources
     *
     * @param float $lat
     * @param float $lng
     * @param int $limit
     * @param string $type Optional resource type
     * @param string $status Optional status filter
     * @return array
     */
    public static function getNearest($lat, $lng, $limit = 5, $type = null, $status = null) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) 
                * cos(radians(lat)) 
                * cos(radians(lng) - radians(:lng)) 
                + sin(radians(:lat)) 
                * sin(radians(lat)))) AS distance 
                FROM emergency_resources
                WHERE 1=1";
        
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'limit' => $limit
        ];
        
        if ($type) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY distance
                  LIMIT :limit";
        
        return static::raw($sql, $params);
    }

    /**
     * Get resource statistics
     *
     * @return array
     */
    public static function getStatistics() {
        $sql = "SELECT 
                type,
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'available' THEN 1 END) as available,
                COUNT(CASE WHEN status = 'full' THEN 1 END) as full,
                COUNT(CASE WHEN status = 'closed' THEN 1 END) as closed,
                SUM(capacity) as total_capacity
                FROM emergency_resources
                GROUP BY type";
        
        return static::raw($sql);
    }

    /**
     * Create resource with contact info
     *
     * @param array $data Resource data
     * @param array $contact_info Contact information
     * @return int|false
     */
    public static function createWithContact($data, $contact_info) {
        $data['contact_info'] = json_encode($contact_info);
        return static::create($data);
    }

    /**
     * Update resource contact info
     *
     * @param int $id
     * @param array $contact_info
     * @return bool
     */
    public static function updateContactInfo($id, $contact_info) {
        return static::update($id, [
            'contact_info' => json_encode($contact_info)
        ]);
    }

    /**
     * Get resources with available capacity
     *
     * @param string $type Optional resource type
     * @param int $min_capacity Minimum required capacity
     * @return array
     */
    public static function getWithCapacity($type = null, $min_capacity = 1) {
        $sql = "SELECT *
                FROM emergency_resources
                WHERE capacity >= :min_capacity
                AND status = 'available'";
        
        $params = ['min_capacity' => $min_capacity];
        
        if ($type) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        $sql .= " ORDER BY capacity DESC";
        
        return static::raw($sql, $params);
    }

    /**
     * Calculate total capacity by type
     *
     * @param string $type
     * @return int
     */
    public static function getTotalCapacity($type) {
        $sql = "SELECT SUM(capacity) as total
                FROM emergency_resources
                WHERE type = :type
                AND status = 'available'";
        
        $result = static::raw($sql, ['type' => $type]);
        return (int)($result[0]['total'] ?? 0);
    }

    /**
     * Search resources
     *
     * @param string $query
     * @return array
     */
    public static function search($query) {
        $sql = "SELECT *
                FROM emergency_resources
                WHERE name LIKE :query
                OR JSON_EXTRACT(contact_info, '$.address') LIKE :query
                ORDER BY name";
        
        return static::raw($sql, [
            'query' => "%{$query}%"
        ]);
    }
}