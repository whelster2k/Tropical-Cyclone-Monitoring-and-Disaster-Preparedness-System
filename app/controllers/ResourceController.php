<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Resource;

class ResourceController extends Controller {
    /**
     * Display list of emergency resources
     */
    public function indexAction() {
        $summaries = Resource::getComprehensiveSummary();
        $this->render('resources/index', [
            'title' => 'Emergency Resources',
            'summaries' => $summaries
        ]);
    }

    /**
     * Display details of a specific resource
     */
    public function showAction($id) {
        $resource = Resource::getAvailability($id);
        
        if (!$resource) {
            $_SESSION['error'] = 'Resource not found';
            $this->redirect('/resources');
            return;
        }

        $this->render('resources/show', [
            'resource' => $resource
        ]);
    }

    /**
     * Display nearby emergency resources
     */
    public function nearbyAction() {
        $lat = $this->get('lat');
        $lng = $this->get('lng');
        $radius = $this->get('radius', 50); // Default 50km radius
        $type = $this->get('type', 'all');

        // Get comprehensive summaries
        $summaries = Resource::getComprehensiveSummary();

        // Get all active resources if no location provided
        $resources = $lat && $lng 
            ? Resource::getNearby($lat, $lng, $radius, $type)
            : Resource::getActive();

        if ($this->isApiRequest()) {
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'resources' => $resources,
                    'summaries' => $summaries
                ]
            ]);
            return;
        }

        $this->render('resources/nearby', [
            'resources' => $resources,
            'summaries' => $summaries,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'type' => $type
        ]);
    }

    /**
     * Update resource availability
     */
    public function updateAvailabilityAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Method not allowed'
            ], 405);
            return;
        }

        $id = $this->post('id');
        $quantity = $this->post('quantity');
        $status = $this->post('status', null);

        if (!$id || !isset($quantity)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Missing required fields'
            ], 400);
            return;
        }

        $success = Resource::updateAvailability($id, $quantity, $status);
        
        if ($success) {
            $resource = Resource::getAvailability($id);
            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'resource' => $resource
                ]
            ]);
        } else {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to update resource availability'
            ], 500);
        }
    }

    /**
     * Helper method to check if request is API call
     */
    private function isApiRequest() {
        return isset($_SERVER['HTTP_ACCEPT']) && 
               strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
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