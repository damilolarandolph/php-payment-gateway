<?php

use Gateway\Routing\Router;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require __DIR__ . "/../common/routing/router.php";
require_once __DIR__ . "/src/controllers/oauth.php";
require_once __DIR__ . "/src/controllers/migration.php";
require_once __DIR__ . "/../common/utils/env-parser.php";
header("Content-Type: application/json");
header("Cache-Control: no-cache");

// Load env file
\Gateway\Util\EnvParser::load(__DIR__ . "/src/.env");
$router = new Router();

$router->post("/oauth/authorize", OauthController::class, 'authorize');
$router->get("/migrate", MigrationController::class, 'migrate');


$router->navigate();
