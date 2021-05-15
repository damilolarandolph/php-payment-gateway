<?php
require_once __DIR__ . "/../../../../common/utils/random_256.php";

class Consumer
{
    public $id;
    public $name;
    public $messengingEndpoint;
    public $secret;

    public function __construct()
    {
        $this->secret =  random256Hex();
    }
}
