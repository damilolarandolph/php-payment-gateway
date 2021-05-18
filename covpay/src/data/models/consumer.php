<?php
require_once __DIR__ . "/../../../../common/utils/random_256.php";


class Consumer
{
    public $apiKey;
    public $apiSecret;
    public $bankAccount;
    public $bankBIC;
    public $token;
    public $refreshToken;
    public $name;

    public function __construct()
    {
        $this->apiSecret  =  random256Hex();
    }
}
