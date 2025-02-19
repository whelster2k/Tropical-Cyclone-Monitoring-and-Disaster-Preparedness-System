<?php

namespace App\Models;

use App\Core\Model;

class HazardZone extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'hazard_zones';

    /**
     * Get hazard zones by type
     *
     * @param string $type
     * @return array
     */
    public static function getByType($type) {
        return static::where(['type' => $type]);
    }

    /**
     * Get hazard zones by risk level
     *
     * @param string $risk_level
     * @return array
     */
    public static function getByRiskLevel($risk_level) {
        return static::where(['risk_level' => $risk_level]);
    }

    /**
     * Get hazard zones in area
     *
     * @param float $lat
     * @param float $lng
     * @param float $radius in kilometers
     * @return array
     */
    public static function getInArea($lat, $lng, $radius) {
        $sql = "SELECT *, 
                ST_Distance_Sphere(
                    POINT(:lng, :lat),
                    ST_Centroid(ST_GeomFromGeoJSON(boundary_points))
                ) / 1000 as distance
                FROM hazard_zones
                HAVING distance < :radius
                ORDER BY distance";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ]);
    }

    /**
     * Check if a point is within any hazard zone
     *
     * @param float $lat
     * @param float $lng
     * @return array
     */
    public static function checkPoint($lat, $lng) {
        $sql = "SELECT *
                FROM hazard_zones
                WHERE ST_Contains(
                    ST_GeomFromGeoJSON(boundary_points),
                    POINT(:lng, :lat)
                )";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng
        ]);
    }

    /**
     * Get overlapping hazard zones
     *
     * @param array $boundary_points GeoJSON polygon
     * @return array
     */
    public static function getOverlapping($boundary_points) {
        $sql = "SELECT *
                FROM hazard_zones
                WHERE ST_Intersects(
                    ST_GeomFromGeoJSON(:new_boundary),
                    ST_GeomFromGeoJSON(boundary_points)
                )";
        
        return static::raw($sql, [
            'new_boundary' => json_encode($boundary_points)
        ]);
    }

    /**
     * Calculate area of hazard zone
     *
     * @param int $id
     * @return float Area in square kilometers
     */
    public static function calculateArea($id) {
        $sql = "SELECT ST_Area(
                    ST_GeomFromGeoJSON(boundary_points)
                ) * 111.32 * 111.32 as area
                FROM hazard_zones
                WHERE id = :id";
        
        $result = static::raw($sql, ['id' => $id]);
        return $result[0]['area'] ?? 0;
    }

    /**
     * Get population affected by hazard zone
     *
     * @param int $id
     * @return int
     */
    public static function getAffectedPopulation($id) {
        $zone = static::find($id);
        if (!$zone) {
            return 0;
        }

        $sql = "SELECT COUNT(*) as count
                FROM users
                WHERE ST_Contains(
                    ST_GeomFromGeoJSON(:boundary),
                    POINT(location_lng, location_lat)
                )";
        
        $result = static::raw($sql, [
            'boundary' => $zone['boundary_points']
        ]);
        
        return $result[0]['count'] ?? 0;
    }

    /**
     * Get hazard zone statistics
     *
     * @return array
     */
    public static function getStatistics() {
        $sql = "SELECT 
                type,
                risk_level,
                COUNT(*) as count,
                SUM(ST_Area(ST_GeomFromGeoJSON(boundary_points)) * 111.32 * 111.32) as total_area
                FROM hazard_zones
                GROUP BY type, risk_level
                ORDER BY type, FIELD(risk_level, 'low', 'medium', 'high', 'extreme')";
        
        return static::raw($sql);
    }

    /**
     * Get nearest hazard zones to a point
     *
     * @param float $lat
     * @param float $lng
     * @param int $limit
     * @return array
     */
    public static function getNearest($lat, $lng, $limit = 5) {
        $sql = "SELECT *, 
                ST_Distance_Sphere(
                    POINT(:lng, :lat),
                    ST_Centroid(ST_GeomFromGeoJSON(boundary_points))
                ) / 1000 as distance
                FROM hazard_zones
                ORDER BY distance
                LIMIT :limit";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng,
            'limit' => $limit
        ]);
    }

    /**
     * Get updates since a specific timestamp
     *
     * @param string $since Timestamp
     * @return array
     */
    public static function getUpdates($since) {
        $sql = "SELECT * FROM hazard_zones 
                WHERE updated_at > :since
                ORDER BY updated_at DESC";
        
        return static::raw($sql, ['since' => $since]);
    }

    /**
     * Update hazard zone risk level in real-time
     *
     * @param int $id
     * @param string $risk_level
     * @param string $description
     * @return bool
     */
    public static function updateRiskLevel($id, $risk_level, $description = null) {
        $data = ['risk_level' => $risk_level];
        if ($description) {
            $data['description'] = $description;
        }
        return static::update($id, $data);
    }

    /**
     * Get active alerts for hazard zones
     *
     * @return array
     */
    public static function getActiveAlerts() {
        $sql = "SELECT hz.*, a.title as alert_title, a.description as alert_description
                FROM hazard_zones hz
                JOIN alerts a ON ST_Contains(
                    ST_GeomFromGeoJSON(hz.boundary_points),
                    ST_GeomFromGeoJSON(a.affected_areas)
                )
                WHERE a.end_time > NOW()
                AND a.start_time <= NOW()
                ORDER BY a.severity DESC, a.start_time DESC";
        
        return static::raw($sql);
    }

    /**
     * Get real-time weather data for hazard zones
     *
     * @return array
     */
    public static function getWeatherData() {
        // This would typically integrate with a weather API
        // For now, we'll return sample data
        return [
            'rainfall' => rand(0, 100), // mm/hr
            'wind_speed' => rand(0, 150), // km/h
            'temperature' => rand(20, 35), // °C
            'humidity' => rand(60, 100), // %
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Calculate dynamic risk score based on real-time conditions
     *
     * @param float $lat
     * @param float $lng
     * @return array
     */
    public static function calculateRiskScore($lat, $lng) {
        $hazards = static::checkPoint($lat, $lng);
        $weather = static::getWeatherData();
        
        $risk_weights = [
            'low' => 1,
            'medium' => 2,
            'high' => 3,
            'extreme' => 4
        ];
        
        $type_weights = [
            'flood' => 1,
            'landslide' => 1,
            'storm_surge' => 1.2,
            'wind_damage' => 0.8
        ];
        
        // Apply weather condition modifiers
        if ($weather['rainfall'] > 50) { // Heavy rain
            $type_weights['flood'] *= 1.5;
            $type_weights['landslide'] *= 1.3;
        }
        
        if ($weather['wind_speed'] > 100) { // Strong winds
            $type_weights['wind_damage'] *= 1.5;
            $type_weights['storm_surge'] *= 1.3;
        }
        
        $total_score = 0;
        $max_possible_score = 0;
        $risks = [];
        
        foreach ($hazards as $hazard) {
            $risk_weight = $risk_weights[$hazard['risk_level']];
            $type_weight = $type_weights[$hazard['type']];
            $score = $risk_weight * $type_weight;
            
            $total_score += $score;
            $max_possible_score += 4 * $type_weight; // 4 is max risk weight
            
            $risks[] = [
                'type' => $hazard['type'],
                'risk_level' => $hazard['risk_level'],
                'score' => $score,
                'weather_impact' => [
                    'rainfall' => $weather['rainfall'],
                    'wind_speed' => $weather['wind_speed']
                ]
            ];
        }
        
        $normalized_score = $max_possible_score > 0 
            ? ($total_score / $max_possible_score) * 100 
            : 0;
        
        return [
            'total_score' => $total_score,
            'normalized_score' => $normalized_score,
            'risks' => $risks,
            'weather_conditions' => $weather,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
} 