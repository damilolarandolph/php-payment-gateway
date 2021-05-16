<?php
require_once __DIR__ . "/../../../../common/utils/random_256.php";

class Consumer
{
    public $apiKey;
    public $apiSecret;

    public function __construct()
    {
        $this->apiSecret = random256Hex();
    }
}
