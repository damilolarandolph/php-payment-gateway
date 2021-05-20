<?php

namespace Gateway\Routing;

use Error;

require_once __DIR__ . '/route-map.php';
require_once __DIR__ . "/route.php";






class Router
{
    /**
     * @var RouteMap $routeMap
     */
    private $routeMap;


    public function __construct()
    {
        $this->routeMap = new RouteMap();
    }



    public function get($path, $controller, $handler)
    {
        $this->routeMap->addRoute(RouteMethods::$GET, $path, $controller, $handler);
    }
    public function post($path, $controller, $handler)
    {
        $this->routeMap->addRoute(RouteMethods::$POST, $path, $controller, $handler);
    }


    public function navigate()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "OPTIONS") {
            http_response_code(200);
            die();
        }
        $this->routeMap->invokeRoute($method, $this->getUrlPath());
    }

    private function getUrlPath()
    {
        $link =     (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlComponents = parse_url($link);
        return $urlComponents['path'];
    }
}
