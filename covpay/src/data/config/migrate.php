<?php
require_once __DIR__ . "/config.php";

try {
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
        PDO::ATTR_EMULATE_PREPARES   => FALSE,
    );
    $dbConn = new PDO("mysql:host=" . MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, $opt);

    $dbConn->exec("DROP DATABASE IF EXISTS " . "`" .  MYSQL_DB . "` ;");
    $dbConn->exec("CREATE DATABASE " . "`" .  MYSQL_DB . "` ;");
    $dbConn->exec("USE " .   MYSQL_DB . " ;");
    $sqlFile = file_get_contents(__DIR__ . "/gateway.sql");
    $dbConn->exec($sqlFile);
    echo "DATABASE MIGRATED SUCCESSFULLY";
} catch (Exception $e) {
    echo "AN ERROR OCCURRED";
    echo $e->getMessage();
}
