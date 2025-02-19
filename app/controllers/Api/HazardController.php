<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\HazardZone;

class HazardController extends Controller {
    /**
     * Create a new hazard zone
     */
    public function createAction() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Method not allowed');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $required_fields = ['name', 'type', 'risk_level', 'description'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new \Exception("Missing required field: {$field}");
                }
            }

            // Validate risk level
            $valid_risk_levels = ['low', 'medium', 'high', 'extreme'];
            if (!in_array($data['risk_level'], $valid_risk_levels)) {
                throw new \Exception('Invalid risk level');
            }

            // Validate hazard type
            $valid_types = ['flood', 'landslide', 'storm_surge', 'wind_damage'];
            if (!in_array($data['type'], $valid_types)) {
                throw new \Exception('Invalid hazard type');
            }

            // Create new hazard zone
            $hazard_id = HazardZone::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'risk_level' => $data['risk_level'],
                'description' => $data['description']
            ]);

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Hazard zone created successfully',
                'data' => [
                    'id' => $hazard_id
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a hazard zone
     */
    public function deleteAction($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                throw new \Exception('Method not allowed');
            }

            if (!$id) {
                throw new \Exception('Hazard zone ID is required');
            }

            $success = HazardZone::delete($id);
            if (!$success) {
                throw new \Exception('Failed to delete hazard zone');
            }

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Hazard zone deleted successfully'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Automatically define hazard zones based on weather conditions
     */
    private function defineAutomaticHazardZones($weather) {
        $hazards = [];

        // Rainfall-based hazards
        if ($weather['rainfall'] >= 50) { // Heavy rainfall
            $hazards[] = [
                'name' => 'Flood Risk Zone',
                'type' => 'flood',
                'risk_level' => $weather['rainfall'] >= 100 ? 'extreme' : 
                               ($weather['rainfall'] >= 75 ? 'high' : 'medium'),
                'description' => "Automatic flood warning due to heavy rainfall ({$weather['rainfall']} mm/hr). " .
                               "Areas prone to flooding should take necessary precautions."
            ];
        }

        // Wind-based hazards
        if ($weather['wind_speed'] >= 80) { // Strong winds
            $hazards[] = [
                'name' => 'Wind Hazard Zone',
                'type' => 'wind_damage',
                'risk_level' => $weather['wind_speed'] >= 120 ? 'extreme' : 
                               ($weather['wind_speed'] >= 100 ? 'high' : 'medium'),
                'description' => "High wind alert ({$weather['wind_speed']} km/h). " .
                               "Risk of structural damage and flying debris."
            ];
        }

        // Combined weather conditions for storm surge
        if ($weather['wind_speed'] >= 100 && $weather['rainfall'] >= 30) {
            $hazards[] = [
                'name' => 'Storm Surge Risk Zone',
                'type' => 'storm_surge',
                'risk_level' => ($weather['wind_speed'] >= 120 && $weather['rainfall'] >= 50) ? 'extreme' : 'high',
                'description' => "Storm surge warning due to combined high winds and rainfall. " .
                               "Coastal areas should be on high alert."
            ];
        }

        // Landslide risk based on rainfall and humidity
        if ($weather['rainfall'] >= 40 && $weather['humidity'] >= 85) {
            $hazards[] = [
                'name' => 'Landslide Risk Zone',
                'type' => 'landslide',
                'risk_level' => $weather['rainfall'] >= 80 ? 'extreme' : 
                               ($weather['rainfall'] >= 60 ? 'high' : 'medium'),
                'description' => "Landslide risk warning due to sustained rainfall and high humidity. " .
                               "Mountainous and sloped areas are at risk."
            ];
        }

        return $hazards;
    }

    /**
     * Get current hazard zones with automatic updates
     */
    public function getAutoUpdateAction() {
        try {
            date_default_timezone_set('Asia/Manila');
            $weather = HazardZone::getWeatherData();
            $automaticHazards = $this->defineAutomaticHazardZones($weather);
            
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'hazards' => $automaticHazards,
                    'weather' => $weather,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 