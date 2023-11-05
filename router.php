<?php

namespace router;
class Router {
    private $routes = [];
    private $base;
    function __construct($base = "") {
        $this->base = $base;
        $this->setRoute('/404', 'get', function (){
            echo 'Page not found';
        });
    }

    /**
     * Return base
     * @return string
     */
    public function getBase(): string
    {
        return $this->base;
    }

    /**
     * Set request method GET
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function get(string $path, callable $callback)
    {
        $this->setRoute($path,"get", $callback);
    }

    /**
     * Set request method POST
     * @param $path
     * @param $callback
     * @return void
     */
    public function post($path, $callback)
    {
        $this->setRoute($path,"post", $callback);
    }

    /**
     * Set request method PUT
     * @param $path
     * @param $callback
     * @return void
     */
    public function put($path, $callback)
    {
        $this->setRoute($path,"put", $callback);
    }

    /**
     * Set request method PATCH
     * @param $path
     * @param $callback
     * @return void
     */
    public function patch($path, $callback)
    {
        $this->setRoute($path,"patch", $callback);
    }

    /**
     * Set request method DELETE
     * @param $path
     * @param $callback
     * @return void
     */
    public function delete($path, $callback)
    {
        $this->setRoute($path,"delete", $callback);
    }

    /**
     * Set not found callback
     * @param $callback
     * @return void
     */
    public function notFound($callback)
    {
        $this->setRoute('/404', 'get', $callback);
    }

    /**
     * Append the route
     * @param $path
     * @param $method
     * @param $callback
     * @return void
     */
    public function setRoute($path, $method, $callback)
    {
        $this->routes[strtoupper($method)][Router::normalizePath($path, $this->base)] = $callback;
    }

    /**
     * Get the route
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function getRoute(string $path, string $method)
    {
        return $this->routes[strtoupper($method)][Router::normalizePath($path, $this->base)];
    }
    /**
     * Get request method
     * @return string
     */
    public static function getMethod(): string
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Get uri without query string
     * @return string
     */
    public static function getPath(): string
    {
        $path = explode("?", $_SERVER["REQUEST_URI"])[0];
        return Router::normalizePath($path);
    }

    /**
     * Return normalized path
     * @param string $path
     * @param string $base
     * @return string
     */
    private static function normalizePath(string $path, string $base = ""): string
    {
        $path_with_prefix = $base;
        $path_with_prefix .= $path[0] != "/" ? "/" : "";
        $path_with_prefix .= $path;

        $parts = explode("/", $path_with_prefix);
        $normalized = implode('/', array_filter($parts, function ($part) {
            return $part != '';
        }));
        return '/' . $normalized;
    }

    /**
     * Get query param
     * @param string $key
     * @return string
     */
    public static function getQueryParam(string $key): string
    {
        return $_GET[$key];
    }

    /**
     * Match url with route. If match is success, return match result
     * @param string $url
     * @param string $route
     * @param string[] $matches
     * @return false|int
     */
    public static function matchUrl(string $url, string $route, &$matches)
    {
        $selector_pattern = '/\/:([^\/]+)/'; // :example_param
        $param_pattern = "/([^/*]+)";
        $pattern = preg_replace($selector_pattern, $param_pattern, $route);
        // Select a suitable route
        $match_result = preg_match("#^" . $pattern ."$#", $url, $matches);
        array_shift($matches); // remove the first item
        return $match_result;
    }

    /**
     * Match request url with routes and call callback
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function resolve (string $path, string $method): bool
    {
        if (isset($this->routes[$method]))
            foreach ($this->routes[$method] as $route => $callback) {
                if (Router::matchUrl($path, $route, $params)) {
                    call_user_func($callback, ...$params);
                    return true;
                }
            }
        // Not found page
        call_user_func($this->getRoute('/404', 'get'));
        return false;
    }
}