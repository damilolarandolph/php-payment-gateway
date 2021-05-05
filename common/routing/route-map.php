<?php

namespace Gateway\Routing;

use Error;

require_once __DIR__ . '/route.php';



class RouteMap
{

    /**
     * @var Route[]
     */
    private $getRoutes;
    /**
     * @var Route[]
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
        $route = $currTable['.'];
        $controller = new $route->clazz();
        $controller->{$route->method}();
    }
    /**
     * @param string $path 
     * @param Closure|\Gateway\REST\Controller  $handler
     */
    public function addRoute($method, $path, $controller, $handler)
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
                $currTable[$item] = array();
            }
            $currTable = &$currTable[$item];
        }
        $currTable['.'] = new Route($controller, $handler);
    }
}


class Route
{
    public $clazz;
    public $method;
    public function __construct($clazz, $method)
    {
        $this->clazz = $clazz;
        $this->method = $method;
    }
}
