<?php

namespace App\Controllers\Api;

use App\Core\Controller;

abstract class ApiController extends Controller {
    /**
     * Send a JSON response
     *
     * @param mixed $data
     * @param int $status
     * @return void
     */
    protected function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Send a success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return void
     */
    protected function success($data = null, $message = 'Success', $status = 200) {
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        $this->jsonResponse($response, $status);
    }

    /**
     * Send an error response
     *
     * @param string $message
     * @param int $status
     * @param array $errors
     * @return void
     */
    protected function error($message = 'Error', $status = 400, $errors = []) {
        $response = [
            'status' => 'error',
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        $this->jsonResponse($response, $status);
    }

    /**
     * Get JSON input data
     *
     * @return array
     */
    protected function getJsonInput() {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }

    /**
     * Validate required fields
     *
     * @param array $data
     * @param array $fields
     * @return array Empty array if valid, array of missing fields if invalid
     */
    protected function validateRequired($data, $fields) {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }

    /**
     * Handle validation errors
     *
     * @param array $missing
     * @return void
     */
    protected function handleValidationErrors($missing) {
        if (!empty($missing)) {
            $this->error(
                'Validation failed',
                422,
                ['missing_fields' => $missing]
            );
        }
    }

    /**
     * Get pagination parameters
     *
     * @param int $default_limit
     * @return array
     */
    protected function getPaginationParams($default_limit = 10) {
        $page = max(1, intval($this->get('page', 1)));
        $limit = max(1, intval($this->get('limit', $default_limit)));
        $offset = ($page - 1) * $limit;

        return [
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * Format pagination response
     *
     * @param array $items
     * @param int $total
     * @param array $params
     * @return array
     */
    protected function paginationResponse($items, $total, $params) {
        return [
            'items' => $items,
            'pagination' => [
                'total' => $total,
                'per_page' => $params['limit'],
                'current_page' => $params['page'],
                'total_pages' => ceil($total / $params['limit'])
            ]
        ];
    }

    /**
     * Get sort parameters
     *
     * @param array $allowed_fields
     * @param string $default_field
     * @param string $default_direction
     * @return array
     */
    protected function getSortParams($allowed_fields, $default_field, $default_direction = 'desc') {
        $field = $this->get('sort_by', $default_field);
        $direction = strtolower($this->get('sort_direction', $default_direction));

        if (!in_array($field, $allowed_fields)) {
            $field = $default_field;
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = $default_direction;
        }

        return [
            'field' => $field,
            'direction' => $direction
        ];
    }
} 