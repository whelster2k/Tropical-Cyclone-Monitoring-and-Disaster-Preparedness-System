<?php

namespace App\Models;

use App\Core\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Alert extends Model {
    /**
     * The table associated with the model
     */
    const TABLE = 'alerts';

    /**
     * Get active alerts
     *
     * @return array
     */
    public static function getActive() {
        $sql = "SELECT * FROM alerts 
                WHERE end_time > NOW() 
                ORDER BY severity DESC, start_time DESC";
        
        return static::raw($sql);
    }

    /**
     * Create a new alert and notify affected users
     *
     * @param array $data Alert data
     * @param bool $notify Whether to send notifications
     * @return int|false
     */
    public static function createAlert($data, $notify = true) {
        $db = static::getDB();
        
        try {
            $db->beginTransaction();
            
            // Create the alert
            $alert_id = static::create($data);
            
            if ($notify) {
                // Get affected users based on location
                $affected_areas = json_decode($data['affected_areas'], true);
                $users = [];
                
                foreach ($affected_areas as $area) {
                    $area_users = User::getUsersInArea(
                        $area['lat'],
                        $area['lng'],
                        $area['radius']
                    );
                    $users = array_merge($users, $area_users);
                }
                
                // Remove duplicates
                $users = array_unique($users, SORT_REGULAR);
                
                // Send notifications
                foreach ($users as $user) {
                    static::notifyUser($user, $data);
                }
            }
            
            $db->commit();
            return $alert_id;
            
        } catch (\Exception $e) {
            $db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Notify a user about an alert
     *
     * @param array $user User data
     * @param array $alert Alert data
     * @return bool
     */
    protected static function notifyUser($user, $alert) {
        // Get user's notification preferences
        $subscriptions = static::raw(
            "SELECT * FROM alert_subscriptions WHERE user_id = :user_id",
            ['user_id' => $user['id']]
        );
        
        foreach ($subscriptions as $sub) {
            switch ($sub['alert_type']) {
                case 'email':
                    static::sendEmail($user, $alert);
                    break;
                    
                case 'sms':
                    static::sendSMS($user, $alert);
                    break;
                    
                case 'push':
                    static::sendPushNotification($user, $alert);
                    break;
            }
        }
        
        return true;
    }

    /**
     * Send email notification
     *
     * @param array $user User data
     * @param array $alert Alert data
     * @return bool
     */
    protected static function sendEmail($user, $alert) {
        $config = require APP_ROOT . '/config/app.php';
        $mail_config = $config['notifications']['email'];
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_ENCRYPTION');
            $mail->Port = getenv('MAIL_PORT');
            
            // Recipients
            $mail->setFrom($mail_config['from_address'], $mail_config['from_name']);
            $mail->addAddress($user['email'], User::getFullName($user));
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = "Weather Alert: {$alert['title']}";
            $mail->Body = static::generateEmailBody($alert);
            
            return $mail->send();
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification
     *
     * @param array $user User data
     * @param array $alert Alert data
     * @return bool
     */
    protected static function sendSMS($user, $alert) {
        $config = require APP_ROOT . '/config/app.php';
        $sms_config = $config['notifications']['sms'];
        
        // Implement SMS gateway integration here
        // This is a placeholder for actual SMS sending logic
        
        return true;
    }

    /**
     * Send push notification
     *
     * @param array $user User data
     * @param array $alert Alert data
     * @return bool
     */
    protected static function sendPushNotification($user, $alert) {
        // Implement push notification logic here
        // This is a placeholder for actual push notification logic
        
        return true;
    }

    /**
     * Generate email body for alert
     *
     * @param array $alert Alert data
     * @return string
     */
    protected static function generateEmailBody($alert) {
        $severity_colors = [
            'low' => '#3498db',
            'medium' => '#f1c40f',
            'high' => '#e67e22',
            'extreme' => '#e74c3c'
        ];
        
        $color = $severity_colors[$alert['severity']] ?? '#3498db';
        
        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background-color: {$color}; color: white; padding: 20px; text-align: center;'>
                    <h1 style='margin: 0;'>{$alert['title']}</h1>
                    <p style='margin: 10px 0 0;'>Severity: " . ucfirst($alert['severity']) . "</p>
                </div>
                
                <div style='padding: 20px; background-color: #f9f9f9;'>
                    <p style='margin-top: 0;'>{$alert['description']}</p>
                    
                    <div style='margin-top: 20px;'>
                        <p><strong>Start Time:</strong> " . date('F j, Y g:i A', strtotime($alert['start_time'])) . "</p>
                        <p><strong>End Time:</strong> " . date('F j, Y g:i A', strtotime($alert['end_time'])) . "</p>
                    </div>
                    
                    <div style='margin-top: 20px; padding: 15px; background-color: #fff; border-left: 4px solid {$color};'>
                        <h3 style='margin-top: 0;'>Safety Instructions:</h3>
                        <ul style='margin-bottom: 0;'>
                            <li>Stay informed about the latest updates</li>
                            <li>Follow evacuation orders if issued</li>
                            <li>Keep emergency supplies ready</li>
                            <li>Stay away from hazardous areas</li>
                        </ul>
                    </div>
                </div>
                
                <div style='padding: 20px; text-align: center; color: #666;'>
                    <p style='margin: 0;'>This is an automated message from the PAGASA Cyclone Monitoring System</p>
                </div>
            </div>
        ";
    }

    /**
     * Get alerts for a specific area
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return array
     */
    public static function getAlertsForLocation($lat, $lng) {
        $sql = "SELECT * FROM alerts 
                WHERE end_time > NOW() 
                AND JSON_CONTAINS(
                    affected_areas,
                    JSON_OBJECT(
                        'lat', :lat,
                        'lng', :lng
                    ),
                    '$[*]'
                )
                ORDER BY severity DESC, start_time DESC";
        
        return static::raw($sql, [
            'lat' => $lat,
            'lng' => $lng
        ]);
    }

    /**
     * Get alert statistics
     *
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public static function getStatistics($start_date, $end_date) {
        $sql = "SELECT 
                COUNT(*) as total_alerts,
                COUNT(CASE WHEN severity = 'low' THEN 1 END) as low_severity,
                COUNT(CASE WHEN severity = 'medium' THEN 1 END) as medium_severity,
                COUNT(CASE WHEN severity = 'high' THEN 1 END) as high_severity,
                COUNT(CASE WHEN severity = 'extreme' THEN 1 END) as extreme_severity
                FROM alerts
                WHERE start_time BETWEEN :start_date AND :end_date";
        
        return static::raw($sql, [
            'start_date' => $start_date,
            'end_date' => $end_date
        ])[0];
    }
} 