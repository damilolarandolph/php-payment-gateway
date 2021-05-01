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

    public function get($path, $handler)
    {
        $route = new Route();
        $route->handler = $handler;
        $route->path = $path;
        $route->method = RouteMethods::$GET;
        $this->routeMap->addRoute($route);
    }

    public function post($path, $handler)
    {
        $route = new Route();
        $route->handler = $handler;
        $route->path = $path;
        $route->method = RouteMethods::$POST;
        $this->routeMap->addRoute($route);
    }

    public function navigate()
    {
        $path = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];

        $this->routeMap->invokeRoute($method, $path);
    }
}
