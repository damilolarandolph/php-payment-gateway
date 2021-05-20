<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../common/utils/env-parser.php";
\Gateway\Util\EnvParser::load(__DIR__ . "/src/.env");
require_once __DIR__ . '/../common/routing/router.php';
require_once __DIR__ . "/src/controllers/home.php";
require_once __DIR__ . "/src/controllers/product.php";
require_once __DIR__ . "/src/controllers/student.php";
require_once __DIR__ . "/src/controllers/migration.php";
header("Cache-Control: no-cache");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

use Gateway\Routing\Router;

$router = new Router();

$router->get("/", HomeController::class, 'home');
$router->get("/migrate", MigrationController::class, 'migrate');
$router->get("/api/student", StudentController::class, "getStudent");
$router->post("/api/student", StudentController::class, "createStudent");
$router->post("/api/student/update", StudentController::class, "saveStudent");
$router->get("/api/products", ProductController::class, "getProducts");
$router->get("/api/product", ProductController::class, "getProduct");
$router->post("/api/product", ProductController::class, "createProduct");



$router->navigate();
