<?php

namespace Gateway\Routing;

use Error;

require_once __DIR__ . '/route.php';



class RouteMap
{

    /**
     * @var array $routes
     */
    private $getRoutes;

    /**
     * 
     */
    private $postRoutes;

    public function __construct()
    {
        $this->getRoutes = array('/' => array());
        $this->postRoutes = array('/' => array());
    }

    public function invokeRoute($method, $path)
    {
        $explodedPath = preg_split('#/#', $path, -1, PREG_SPLIT_NO_EMPTY);
        $routeTable = null;
        if ($method == RouteMethods::$GET) {
            $routeTable = &$this->getRoutes;
        } else {
            $routeTable = &$this->postRoutes;
        }
        $currTable = &$routeTable['/'];
        foreach ($explodedPath as $item) {
            if (!isset($currTable[$item])) {
                throw new Error("Route not found");
            }
            $currTable = &$currTable[$item];
        }

        if (!isset($currTable['.'])) {
            throw new Error("Route not found");
        }
        ($currTable['.']->handler)();
    }
    /**
     * @param string $method
     * @param Route  $route
     */
    public function addRoute($route)
    {
        $explodedPath = preg_split('#/#', $route->path, -1, PREG_SPLIT_NO_EMPTY);
        $routeTable = null;
        if ($route->method == RouteMethods::$GET) {
            $routeTable = &$this->getRoutes;
        } else {
            $routeTable = &$this->postRoutes;
        }
        $currTable = &$routeTable['/'];
        foreach ($explodedPath as $item) {
            if (!isset($currTable[$item])) {
                $currTable[$item] = array();
            }
            $currTable = &$currTable[$item];
        }

        $currTable['.'] = $route;
    }
}


class Route
{
    public $path;
    public $method;
    public $handler;
}
