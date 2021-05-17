<?php
require_once __DIR__ . "/../repositories/consumer-repository.php";
require_once __DIR__ . "/../repositories/account-repository.php";

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
    $sqlFile = file_get_contents(__DIR__ . "/zenith.sql");
    $dbConn->exec($sqlFile);
    echo "DATABASE MIGRATED SUCCESSFULLY";
    $consumerRepo = new ConsumerRepository();
    $accountRepo = new BankAccountRepository();
    $consumer = new Consumer();
    $consumer->apiSecret = bin2hex(random_bytes(32));
    $consumerRepo->save($consumer);
    $bankAccount = new BankAccount();
    $bankAccount->fullName = "Damilola Randolph";
    $bankAccount->phoneNumber = "0234149134";
    $bankAccount->balance = 40000;
    $accountRepo->save($bankAccount);
    echo "DATABASE SEEDED SUCCESSFULLY";
} catch (Exception $e) {
    echo "AN ERROR OCCURRED";
    echo $e->getMessage();
}
