<?php

use Gateway\Routing\Router;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../common/utils/env-parser.php";
\Gateway\Util\EnvParser::load(__DIR__ . "/src/.env");
require __DIR__ . "/../common/routing/router.php";
require_once __DIR__ . "/src/controllers/oauth.php";
require_once __DIR__ . "/src/controllers/migration.php";
require_once __DIR__ . "/src/controllers/messaging.php";
require_once __DIR__ . "/src/controllers/account.php";
require_once __DIR__ . "/src/controllers/home.php";
header("Content-Type: application/json");
header("Cache-Control: no-cache");

// Load env file
$router = new Router();
$router->post("/oauth/authorize", OauthController::class, 'authorize');
$router->get("/oauth/dialog", OauthController::class, 'displayOtpConfirm');
$router->post("/oauth/loginCheck", OauthController::class, 'confirmOtp');
$router->post("/oauth/swap", OauthController::class, 'swapToken');
$router->get("/migrate", MigrationController::class, 'migrate');
$router->post("/api/message", MessageController::class, "message");
$router->post("/api/account", AccountController::class, 'createAccount');
$router->get("/", AccountController::class, "home");


$router->navigate();
