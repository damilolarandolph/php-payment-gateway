<?php

namespace Gateway\Routing;

use Error;

require_once __DIR__ . '/route.php';



class RouteMap
{

    /**
     * @var \Gateway\REST\Controller[]
     */
    private $routes;


    public function __construct()
    {
        $this->routes = array('/' => array());
    }

    public function invokeRoute($method, $path)
    {
        $explodedPath = preg_split('#/#', $path, -1, PREG_SPLIT_NO_EMPTY);
        $routeTable = &$this->routes;

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
        $controller = $currTable['.'];
        if ($method == RouteMethods::$GET) {
            $controller->get();
        } else {
            $controller->post();
        }
    }
    /**
     * @param string $path 
     * @param Closure|\Gateway\REST\Controller  $handler
     */
    public function addRoute($path, $handler)
    {

        if (!($handler instanceof \Gateway\REST\Controller))
            throw new Error("Route handler must be subclasses of Controller clas");
        $explodedPath = preg_split('#/#', $path, -1, PREG_SPLIT_NO_EMPTY);
        $routeTable = &$this->routes;
        $currTable = &$routeTable['/'];
        foreach ($explodedPath as $item) {
            if (!isset($currTable[$item])) {
                $currTable[$item] = array();
            }
            $currTable = &$currTable[$item];
        }
        $currTable['.'] = $handler;
    }
}
