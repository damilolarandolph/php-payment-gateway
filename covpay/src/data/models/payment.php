<?php


class Payment implements JsonSerializable
{
    public $id;
    public $payerPhone;
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

    public function jsonSerialize()
    {
        $map = array();
        $map["payerPhone"] = $this->payerPhone;
        $map["payerName"] = $this->payerName;
        $map["data"] = $this->data;
        $map["amount"] = $this->amount;
        $map["state"] = $this->state;
        $map["id"] = $this->id;
        return $map;
    }
}


abstract class PaymentStates
{
    const PENDING = "pending";
    const SUCCESS = "success";
    const REFUNDED = "refunded";
    const FAILED = "failed";
}
