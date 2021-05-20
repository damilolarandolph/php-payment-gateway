<?php


class CardDetails
{
    public $id;
    public $cardNumber;
    public $pin;
    public $expiry;
    public $cvv;
    public $cardCompany;

    public function __construct()
    {
        $this->cardCompany = "Mastercard";
    }
}
