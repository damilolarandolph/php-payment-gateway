<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../common/utils/env-parser.php";
\Gateway\Util\EnvParser::load(__DIR__ . "/src/.env");
require __DIR__ . "/../common/routing/router.php";
require_once __DIR__ . "/src/controllers/messenging.php";
require_once __DIR__ . "/src/controllers/migration.php";
header("Content-Type: application/json");
header("Cache-Control: no-cache");

use Gateway\Routing\Router;

$router = new Router();

$router->get("/migrate", MigrationController::class, "migrate");
$router->post("/message", MessageController::class, 'post');

$router->navigate();
