<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once __DIR__ . '/../common/routing/router.php';
require_once __DIR__ . "/src/controllers/home-controller.php";
require_once __DIR__ . '/src/controllers/add-consumer-controller.php';

use Gateway\Routing\Router;

$router = new Router();

$router->add("/consumers/add", new AddConsumerController());


$router->navigate();
