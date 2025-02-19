<?php

namespace App\Models;

use App\Core\Model;

class CommunityReport extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'community_reports';

    /**
     * Get pending reports
     *
     * @return array
     */
    public static function getPending() {
        return static::where(['status' => 'pending']);
    }

    /**
     * Get verified reports
     *
     * @return array
     */
    public static function getVerified() {
        return static::where(['status' => 'verified']);
    }

    /**
     * Get reports by type
     *
     * @param string $type
     * @return array
     */
    public static function getByType($type) {
        return static::where(['type' => $type, 'status' => 'verified']);
    }

    /**
     * Get reports in area
     *
     * @param float $lat
     * @param float $lng
     * @param float $radius in kilometers
     * @param string $status Optional status filter
     * @return array
     */
    public static function getInArea($lat, $lng, $radius, $status = null) {
        $sql = "SELECT r.*, 
                u.username,
                (6371 * acos(cos(radians(:lat)) 
                * cos(radians(r.lat)) 
                * cos(radians(r.lng) - radians(:lng)) 
                + sin(radians(:lat)) 
                * sin(radians(r.lat)))) AS distance 
                FROM community_reports r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE 1=1";
        
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ];
        
        if ($status) {
            $sql .= " AND r.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " HAVING distance < :radius 
                  ORDER BY r.created_at DESC, distance";
        
        return static::raw($sql, $params);
    }

    /**
     * Create a new report with images
     *
     * @param array $data Report data
     * @param array $images Array of image paths
     * @return int|false
     */
    public static function createWithImages($data, $images = []) {
        if (!empty($images)) {
            $data['images'] = json_encode($images);
        }
        
        return static::create($data);
    }

    /**
     * Verify a report
     *
     * @param int $id
     * @return bool
     */
    public static function verify($id) {
        return static::update($id, [
            'status' => 'verified'
        ]);
    }

    /**
     * Reject a report
     *
     * @param int $id
     * @return bool
     */
    public static function reject($id) {
        return static::update($id, [
            'status' => 'rejected'
        ]);
    }

    /**
     * Get report statistics
     *
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public static function getStatistics($start_date, $end_date) {
        $sql = "SELECT 
                COUNT(*) as total_reports,
                COUNT(CASE WHEN type = 'flooding' THEN 1 END) as flooding_reports,
                COUNT(CASE WHEN type = 'damage' THEN 1 END) as damage_reports,
                COUNT(CASE WHEN type = 'casualty' THEN 1 END) as casualty_reports,
                COUNT(CASE WHEN type = 'other' THEN 1 END) as other_reports,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_reports,
                COUNT(CASE WHEN status = 'verified' THEN 1 END) as verified_reports,
                COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_reports
                FROM community_reports
                WHERE created_at BETWEEN :start_date AND :end_date";
        
        return static::raw($sql, [
            'start_date' => $start_date,
            'end_date' => $end_date
        ])[0];
    }

    /**
     * Get reports by user
     *
     * @param int $user_id
     * @return array
     */
    public static function getByUser($user_id) {
        return static::where(['user_id' => $user_id]);
    }

    /**
     * Get report with user details
     *
     * @param int $id
     * @return array|false
     */
    public static function getWithUser($id) {
        $sql = "SELECT r.*, 
                u.username,
                u.email,
                u.first_name,
                u.last_name
                FROM community_reports r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.id = :id";
        
        $result = static::raw($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Get recent reports
     *
     * @param int $limit
     * @param string $status Optional status filter
     * @return array
     */
    public static function getRecent($limit = 10, $status = null) {
        $sql = "SELECT r.*, 
                u.username
                FROM community_reports r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE 1=1";
        
        $params = ['limit' => $limit];
        
        if ($status) {
            $sql .= " AND r.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY r.created_at DESC
                  LIMIT :limit";
        
        return static::raw($sql, $params);
    }

    /**
     * Get reports by severity
     *
     * @param string $severity
     * @param string $status Optional status filter
     * @return array
     */
    public static function getBySeverity($severity, $status = null) {
        $conditions = ['severity' => $severity];
        
        if ($status) {
            $conditions['status'] = $status;
        }
        
        return static::where($conditions);
    }

    /**
     * Search reports
     *
     * @param string $query
     * @return array
     */
    public static function search($query) {
        $sql = "SELECT r.*, 
                u.username
                FROM community_reports r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.title LIKE :query 
                OR r.description LIKE :query
                ORDER BY r.created_at DESC";
        
        return static::raw($sql, [
            'query' => "%{$query}%"
        ]);
    }
} 