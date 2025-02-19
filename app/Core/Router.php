<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $params = [];
    private $middleware = [];
    private $base_path;

    public function __construct() {
        // Calculate base path by comparing DOCUMENT_ROOT and SCRIPT_FILENAME
        $this->base_path = str_replace('\\', '/', 
            substr(dirname($_SERVER['SCRIPT_FILENAME']), 
                  strlen($_SERVER['DOCUMENT_ROOT']))
        );
    }

    /**
     * Add a route to the routing table
     * 
     * @param string $route  The route URL
     * @param array  $params Parameters (controller, action, etc.)
     * @param string $method The HTTP method
     * 
     * @return void
     */
    public function add($route, $params = [], $method = 'GET') {
        // Convert the route to a regular expression
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$method][$route] = $params;
    }

    /**
     * Add middleware to a route
     * 
     * @param string $route      The route URL
     * @param string $middleware The middleware class name
     * 
     * @return void
     */
    public function middleware($route, $middleware) {
        $this->middleware[$route] = $middleware;
    }

    /**
     * Match the route to the routes in the routing table
     * 
     * @param string $url The route URL
     * @param string $method The HTTP method
     * 
     * @return boolean
     */
    public function match($url, $method = 'GET') {
        foreach ($this->routes[$method] ?? [] as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Dispatch the route and create the controller object and execute the action
     * 
     * @return void
     */
    public function dispatch() {
        $url = $this->removeQueryStringVariables($_SERVER['REQUEST_URI']);
        
        // Remove base path from URL
        if ($this->base_path !== '/') {
            $url = str_replace($this->base_path, '', $url);
        }
        
        // Ensure URL starts with /
        $url = '/' . ltrim($url, '/');
        
        $method = $_SERVER['REQUEST_METHOD'];

        if ($this->match($url, $method)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "App\\Controllers\\$controller";

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action) . 'Action';

                if (method_exists($controller_object, $action)) {
                    // Check middleware
                    if (isset($this->middleware[$url])) {
                        $middleware = new $this->middleware[$url]();
                        if (!$middleware->handle()) {
                            return;
                        }
                    }

                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action in controller $controller not found");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

    /**
     * Convert string with hyphens to StudlyCaps
     * e.g. post-authors => PostAuthors
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    private function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert string with hyphens to camelCase
     * e.g. add-new => addNew
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    private function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL
     * 
     * @param string $url The full URL
     * 
     * @return string
     */
    private function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            return $parts[0];
        }
        return $url;
    }

    /**
     * Get all the routes from the routing table
     * 
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Get the currently matched parameters
     * 
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    public function get($route, $params = []) {
        return $this->add($route, $params, 'GET');
    }

    public function post($route, $params = []) {
        return $this->add($route, $params, 'POST');
    }

    public function put($route, $params = []) {
        return $this->add($route, $params, 'PUT');
    }

    public function delete($route, $params = []) {
        return $this->add($route, $params, 'DELETE');
    }
} 