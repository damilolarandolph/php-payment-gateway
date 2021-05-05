<?php

namespace Gateway\Routing;

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
        $path = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];

        $this->routeMap->invokeRoute($method, $path);
    }
}
