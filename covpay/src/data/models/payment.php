<?php


class Payment
{
    public  $id;
    public  $payerPhone;
    public $payerName;
    public $consumerId;
    public $data;
    public $state;
    public $amount;
    public $cardDetailsId;

    public function __construct()
    {
        $this->state = PaymentStates::PENDING;
    }
}


abstract class PaymentStates
{
    const PENDING = "pending";
    const SUCCESS = "success";
    const REFUNDED = "refunded";
    const FAILED = "failed";
}
