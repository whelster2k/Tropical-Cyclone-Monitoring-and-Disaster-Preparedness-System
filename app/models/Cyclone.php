<?php

namespace App\Models;

use App\Core\Model;

class Cyclone extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'cyclones';

    /**
     * Get active cyclones
     *
     * @return array
     */
    public static function getActive() {
        return static::where(['status' => 'active']);
    }

    /**
     * Get cyclone with its tracking points
     *
     * @param int $id
     * @return array|false
     */
    public static function getWithTrackingPoints($id) {
        $db = static::getDB();
        
        $sql = "SELECT c.*, 
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'lat', ct.lat,
                        'lng', ct.lng,
                        'wind_speed', ct.wind_speed,
                        'pressure', ct.pressure,
                        'timestamp', ct.timestamp
                    )
                ) as tracking_points
                FROM cyclones c
                LEFT JOIN cyclone_tracks ct ON c.id = ct.cyclone_id
                WHERE c.id = :id
                GROUP BY c.id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        
        if ($result) {
            $result['tracking_points'] = $result['tracking_points'] 
                ? array_map('json_decode', explode(',', $result['tracking_points']))
                : [];
        }
        
        return $result;
    }

    /**
     * Add tracking point to cyclone
     *
     * @param int $cyclone_id
     * @param array $data
     * @return bool
     */
    public static function addTrackingPoint($cyclone_id, $data) {
        $db = static::getDB();
        
        $data['cyclone_id'] = $cyclone_id;
        
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO cyclone_tracks ({$columns}) VALUES ({$values})";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    /**
     * Update cyclone's current position
     *
     * @param int $id
     * @param float $lat
     * @param float $lng
     * @param array $additional_data
     * @return bool
     */
    public static function updatePosition($id, $lat, $lng, $additional_data = []) {
        $data = array_merge([
            'current_lat' => $lat,
            'current_lng' => $lng,
            'last_update' => date('Y-m-d H:i:s')
        ], $additional_data);
        
        return static::update($id, $data);
    }

    /**
     * Get cyclones in area
     *
     * @param float $lat
     * @param float $lng
     * @param float $radius in kilometers
     * @return array
     */
    public static function getCyclonesInArea($lat, $lng, $radius) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) 
                * cos(radians(current_lat)) 
                * cos(radians(current_lng) - radians(:lng)) 
                + sin(radians(:lat)) 
                * sin(radians(current_lat)))) AS distance 
                FROM cyclones 
                WHERE status = 'active'
                HAVING distance < :radius 
                ORDER BY distance";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ]);
    }

    /**
     * Get historical cyclones
     *
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public static function getHistorical($start_date, $end_date) {
        $sql = "SELECT * FROM cyclones 
                WHERE formation_date BETWEEN :start_date AND :end_date
                ORDER BY formation_date DESC";
        
        return static::raw($sql, [
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    /**
     * Get cyclone statistics
     *
     * @param int $year
     * @return array
     */
    public static function getStatistics($year) {
        $db = static::getDB();
        
        $sql = "SELECT 
                COUNT(*) as total_cyclones,
                AVG(wind_speed) as avg_wind_speed,
                MAX(wind_speed) as max_wind_speed,
                MIN(pressure) as min_pressure,
                AVG(pressure) as avg_pressure
                FROM cyclones
                WHERE YEAR(formation_date) = :year";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['year' => $year]);
        return $stmt->fetch();
    }

    /**
     * Get monthly cyclone frequency
     *
     * @param int $year
     * @return array
     */
    public static function getMonthlyFrequency($year) {
        $sql = "SELECT 
                MONTH(formation_date) as month,
                COUNT(*) as count
                FROM cyclones
                WHERE YEAR(formation_date) = :year
                GROUP BY MONTH(formation_date)
                ORDER BY month";
        
        return static::raw($sql, ['year' => $year]);
    }

    /**
     * Calculate potential impact area
     *
     * @param int $id
     * @param float $radius in kilometers
     * @return array
     */
    public static function calculateImpactArea($id, $radius) {
        $cyclone = static::find($id);
        
        if (!$cyclone) {
            return [];
        }
        
        // Get points in a circle around the cyclone
        $points = [];
        $lat = $cyclone['current_lat'];
        $lng = $cyclone['current_lng'];
        
        for ($angle = 0; $angle < 360; $angle += 10) {
            $point_lat = $lat + ($radius / 111.32) * cos(deg2rad($angle));
            $point_lng = $lng + ($radius / (111.32 * cos(deg2rad($lat)))) * sin(deg2rad($angle));
            
            $points[] = [
                'lat' => $point_lat,
                'lng' => $point_lng
            ];
        }
        
        return $points;
    }
} 