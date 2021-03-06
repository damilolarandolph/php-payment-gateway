<?php

use Gateway\Routing\Router;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../common/utils/env-parser.php";
\Gateway\Util\EnvParser::load(__DIR__ . "/src/.env");
require __DIR__ . "/../common/routing/router.php";
require_once __DIR__ . "/src/controller/migration-controller.php";
require_once __DIR__ . "/src/controller/payment-controller.php";
require_once __DIR__ . "/src/controller/message-controller.php";
header("Content-Type: application/json");
header("Cache-Control: no-cache");

// Load env file
$router = new Router();

$router->get("/migrate", MigrationController::class, 'migrate');
$router->post("/api/pay", PaymentController::class, 'pay');
$router->post("/api/requestotp", PaymentController::class, 'requestOTP');
$router->post("/api/messenging", MessageController::class, 'message');


$router->navigate();
