<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cyclone;

class CycloneController extends Controller {
    /**
     * Display list of cyclones
     */
    public function indexAction() {
        $this->render('cyclones/index', [
            'title' => 'Active Tropical Cyclones'
        ]);
    }

    /**
     * API endpoint for active cyclones
     */
    public function getActiveAction() {
        try {
            // Simulate active cyclone data for Mindanao
            $cyclones = [
                [
                    'name' => 'Tropical Storm JENNY',
                    'local_name' => 'Bagyong Jenny',
                    'category' => 'Tropical Storm',
                    'lat' => 7.8,
                    'lon' => 126.5,
                    'max_winds' => 85,
                    'gustiness' => 105,
                    'movement' => 'WNW at 15 km/h',
                    'pressure' => 985,
                    'warning_areas' => [
                        'Surigao del Sur',
                        'Agusan del Sur',
                        'Davao Oriental',
                        'Davao de Oro'
                    ],
                    'forecast_track' => [
                        [7.8, 126.5],
                        [8.0, 125.8],
                        [8.2, 125.1],
                        [8.4, 124.4]
                    ],
                    'warning_area_coordinates' => [
                        [8.5, 126.8],
                        [7.2, 126.8],
                        [7.2, 125.5],
                        [8.5, 125.5]
                    ]
                ]
            ];

            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'cyclones' => $cyclones
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to fetch cyclone data'
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

    /**
     * Display details of a specific cyclone
     *
     * @return void
     */
    public function showAction() {
        $id = $this->route_params['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Cyclone ID not provided';
            $this->redirect('/cyclones');
        }

        $cyclone = Cyclone::getWithTrackingPoints($id);
        
        if (!$cyclone) {
            $_SESSION['error'] = 'Cyclone not found';
            $this->redirect('/cyclones');
        }

        $this->render('cyclones/show', ['cyclone' => $cyclone]);
    }

    /**
     * Display cyclone tracking data
     *
     * @return void
     */
    public function trackAction() {
        $cyclones = Cyclone::getActive();
        $this->render('cyclones/track', ['cyclones' => $cyclones]);
    }

    /**
     * Display historical cyclone data
     *
     * @return void
     */
    public function historicalAction() {
        $start_date = date('Y-m-d', strtotime('-1 year'));
        $end_date = date('Y-m-d');
        
        $cyclones = Cyclone::getHistorical($start_date, $end_date);
        $this->render('cyclones/historical', [
            'cyclones' => $cyclones,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }
} 