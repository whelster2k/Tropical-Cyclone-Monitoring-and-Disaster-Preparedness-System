<?php

namespace App\Core;

/**
 * Base Controller
 */
abstract class Controller {
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    public function __construct($route_params) {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args) {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before() {
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after() {
    }

    /**
     * Render a view
     *
     * @param string $view  The view file
     * @param array  $data  Data for the view
     *
     * @return void
     */
    protected function render($view, $data = []) {
        extract($data, EXTR_SKIP);

        $file = APP_ROOT . "/app/views/$view.php";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Redirect to another page
     *
     * @param string $url The URL to redirect to
     * @return void
     */
    protected function redirect($url) {
        $base_url = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_url !== '/') {
            $url = $base_url . $url;
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Send JSON response
     *
     * @param mixed $data The data to send
     * @param int $status HTTP status code
     *
     * @return void
     */
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Get POST data
     *
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     *
     * @return mixed
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     *
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     *
     * @return mixed
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Get uploaded files
     *
     * @param string $key The key to get
     *
     * @return mixed
     */
    protected function files($key = null) {
        if ($key === null) {
            return $_FILES;
        }
        return $_FILES[$key] ?? null;
    }

    /**
     * Check if request is AJAX
     *
     * @return boolean
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Get request method
     *
     * @return string
     */
    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check if request is POST
     *
     * @return boolean
     */
    protected function isPost() {
        return $this->getMethod() === 'POST';
    }

    /**
     * Check if request is GET
     *
     * @return boolean
     */
    protected function isGet() {
        return $this->getMethod() === 'GET';
    }
} 