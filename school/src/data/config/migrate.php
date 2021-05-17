<?php
require_once __DIR__ . "/../models/product.php";
require_once __DIR__ . "/../models/student.php";

try {
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
        PDO::ATTR_EMULATE_PREPARES   => FALSE,
    );
    header("Content-Type: text/html");
    $dbConn = new PDO("mysql:host=" . constant('MYSQL_HOST'), constant('MYSQL_USER'), constant('MYSQL_PASSWORD'), $opt);

    $dbConn->exec("DROP DATABASE IF EXISTS " . "`" .  constant('MYSQL_DB') . "` ;");
    $dbConn->exec("CREATE DATABASE " . "`" .  constant('MYSQL_DB') . "` ;");
    $dbConn->exec("USE " .  constant('MYSQL_DB') . " ;");
    $sqlFile = file_get_contents(__DIR__ . "/school.sql");
    $dbConn->exec($sqlFile);
    echo "DATABASE MIGRATED SUCCESSFULLY<br />";

    echo "DATABASE SEEDED SUCCESSFULLY";
} catch (Exception $e) {
    echo "AN ERROR OCCURRED";
    echo $e->getMessage();
}
