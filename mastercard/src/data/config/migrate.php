<?php
require_once __DIR__ . "/../repositories/consumer-repository.php";
require_once __DIR__ . "/../repositories/card-repository.php";

try {
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
        PDO::ATTR_EMULATE_PREPARES   => FALSE,
    );
    $dbConn = new PDO("mysql:host=" . constant('MYSQL_HOST'), constant('MYSQL_USER'), constant('MYSQL_PASSWORD'), $opt);

    $dbConn->exec("DROP DATABASE IF EXISTS " . "`" .  constant('MYSQL_DB') . "` ;");
    $dbConn->exec("CREATE DATABASE " . "`" .  constant('MYSQL_DB') . "` ;");
    $dbConn->exec("USE " .  constant('MYSQL_DB') . " ;");
    $sqlFile = file_get_contents(__DIR__ . "/mastercard.sql");
    $dbConn->exec($sqlFile);
    echo "DATABASE MIGRATED SUCCESSFULLY";
    $consumerRepo = new ConsumerRepository();
    $accountRepo = new CardRepository();
    $card = new Card();
    $card->number = "5312121321231";
    $card->expiry = "12/21";
    $card->cvv = "813";
    $card->pin = "7898";
    $card->bank = "ZENITH";
    $card->account = "21349090";

    $consumer = new Consumer();
    $consumerRepo->save($consumer);
    $accountRepo->save($card);
    echo "DATABASE SEEDED SUCCESSFULLY";
} catch (Exception $e) {
    echo "AN ERROR OCCURRED";
    echo $e->getMessage();
}
