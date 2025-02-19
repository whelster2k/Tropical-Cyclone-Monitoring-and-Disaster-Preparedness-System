<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\HazardZone;

class HazardController extends Controller {
    /**
     * Display list of hazard zones
     *
     * @return void
     */
    public function indexAction() {
        $hazards = HazardZone::all();
        $weather = HazardZone::getWeatherData();
        $this->render('hazards/index', [
            'hazards' => $hazards,
            'weather' => $weather
        ]);
    }

    /**
     * Display hazard map
     *
     * @return void
     */
    public function mapAction() {
        $hazards = HazardZone::all();
        $this->render('hazards/map', ['hazards' => $hazards]);
    }

    /**
     * Assess hazards for a location
     *
     * @return void
     */
    public function assessAction() {
        $lat = $this->get('lat');
        $lng = $this->get('lng');

        if (!$lat || !$lng) {
            $this->render('hazards/assess');
            return;
        }

        $risk_assessment = HazardZone::calculateRiskScore($lat, $lng);
        $this->render('hazards/assess', [
            'lat' => $lat,
            'lng' => $lng,
            'assessment' => $risk_assessment
        ]);
    }

    /**
     * API endpoint for weather data
     */
    public function weatherAction() {
        try {
            date_default_timezone_set('Asia/Manila');
            $weather = HazardZone::getWeatherData();
            $weather['timestamp'] = date('Y-m-d H:i:s'); // Set current PH time
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'weather' => $weather
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to fetch weather data'
            ], 500);
        }
    }

    /**
     * API endpoint for hazard updates since a timestamp
     */
    public function updatesAction() {
        try {
            $since = $_GET['since'] ?? date('Y-m-d H:i:s', strtotime('-5 minutes'));
            $updates = HazardZone::getUpdates($since);
            
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'updates' => $updates
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to fetch hazard updates'
            ], 500);
        }
    }

    /**
     * API endpoint for active alerts
     */
    public function alertsAction() {
        try {
            $alerts = HazardZone::getActiveAlerts();
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'alerts' => $alerts
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to fetch active alerts'
            ], 500);
        }
    }

    /**
     * API endpoint to update hazard risk level
     */
    public function updateRiskAction() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Method not allowed');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['id']) || !isset($data['risk_level'])) {
                throw new \Exception('Missing required fields');
            }

            $success = HazardZone::updateRiskLevel(
                $data['id'],
                $data['risk_level'],
                $data['description'] ?? null
            );

            if (!$success) {
                throw new \Exception('Failed to update hazard risk level');
            }

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Hazard risk level updated successfully'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to calculate risk score for a location
     */
    public function calculateRiskAction() {
        try {
            if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
                throw new \Exception('Missing coordinates');
            }

            $lat = (float)$_GET['lat'];
            $lng = (float)$_GET['lng'];
            
            $riskScore = HazardZone::calculateRiskScore($lat, $lng);
            
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'risk_assessment' => $riskScore
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method for JSON responses
     */
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 