<?php
require_once __DIR__ . "/../../../../common/utils/random_int.php";
require_once __DIR__ . "/../../../../common/utils/random_256.php";

class BankAccount
{
    public $id;
    public $accountNumber;
    public $fullName;
    public $phoneNumber;
    public $signingKey;
    public $balance;

    public function __construct()
    {
        $this->accountNumber = '219' . getRandomInts(0, 9, 14);
        $this->signingKey = random256Hex();
    }
}
