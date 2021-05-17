<?php
require_once __DIR__ . "/../../../../common/utils/random_int.php";

class Card
{
    public $id;
    public $number;
    public $pin;
    public $cvv;
    public $expiry;
    public $bank;
    public $account;

    public function __construct()
    {
        $prefix = "53";
        $prefix .= getRandomInts(0, 9, 14);
        $this->number = $prefix;
        $this->pin = getRandomInts(0, 9, 4);
        $this->cvv = getRandomInts(0, 9, 3);
        $this->expiry = "12/25";
    }
}
