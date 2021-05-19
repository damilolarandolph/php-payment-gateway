<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once __DIR__ . "/../common/utils/env-parser.php";

use Gateway\Routing\Router;

\Gateway\Util\EnvParser::load(__DIR__ . '/src/.env');
require_once __DIR__ . '/../common/routing/router.php';
require_once __DIR__ . '/src/controllers/add-consumer-controller.php';
require_once __DIR__ . '/src/controllers/home.php';
require_once __DIR__ . '/src/controllers/migration.php';


$router = new Router();

$router->get("/", HomeController::class, 'home');
$router->post("/api/consumer", HomeController::class, 'createConsumer');
$router->get("/api/consumer/continue", AddConsumerController::class, 'continueCreate');
$router->get("/migrate", MigrationController::class, "migrate");


$router->navigate();
