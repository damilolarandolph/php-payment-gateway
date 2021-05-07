<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
    PDO::ATTR_EMULATE_PREPARES   => FALSE,
);

$dbConn = new PDO(
    "mysql:host=" . constant('MYSQL_HOST') . ";dbname=" . constant('MYSQL_DB'),
    constant('MYSQL_USER'),
    constant('MYSQL_PASSWORD'),
    $opt
);
