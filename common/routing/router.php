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

    /**
     * @param string $path
     * @param \Gateway\REST\Controller $controller
     */
    public function add($path, $controller)
    {
        $this->routeMap->addRoute($path, $controller);
    }


    public function navigate()
    {
        $path = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];

        $this->routeMap->invokeRoute($method, $path);
    }
}
