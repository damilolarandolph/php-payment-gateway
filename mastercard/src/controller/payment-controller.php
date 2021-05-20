<?php
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../data/repositories/card-repository.php";
require_once __DIR__ . "/../middleware/consumer-check.php";
require_once __DIR__ . "/../services/messenging-service.php";

class PaymentController
{

    private $cardRepo;

    public function __construct()
    {
        $this->cardRepo = new CardRepository();
    }


    public function requestOTP($requestData)
    {
        $consumer = null;
        try {
            $consumer = ConsumerCheckMiddleware::invoke($requestData);
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        try {
            list($bank, $account) = $this->getSource($requestData);
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        $message = new Message();
        $message->to = $bank;
        $message->from = "MASTERCARD";
        $message->message = array("messageType" => "REQUEST_OTP", "account" => $account);
        extract(MessengingService::sendMessage($message));
        if ($statusCode !== 200) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "OTP_REQUEST_FAILED"));
            die();
        } else {
            echo $response;
            die();
        }
    }

    public function pay($requestData)
    {
        $consumer = null;
        try {
            $consumer = ConsumerCheckMiddleware::invoke($requestData);
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        $errors = checkFields($requestData, array("sourceOfFunds", "destination", "amount"));
        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        extract($requestData);
        $sourceBank = null;
        $sourceAccount = null;
        $destinationBank = null;
        $destinationAccount = null;
        try {
            list($sourceBank, $sourceAccount) = $this->getSource($requestData['sourceOfFunds']);
            list($destinationBank, $destinationAccount) = $this->getSource($requestData['destination']);
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        $message = new Message();
        $message->to = $sourceBank;
        $message->from = "MASTERCARD";
        $message->message = array(
            "messageType" => "WITHDRAW",
            "sourceAccount" => $sourceAccount,
            "destinationBank" => $destinationBank,
            "destinationAccount" => $destinationAccount,
            "amount" => $amount
        );

        if (!empty($otp)) {

            $message->message['otp'] = $otp;
        } else {

            if (empty($token)) {
                http_response_code(401);
                echo json_encode(array("status" => "fail", "message" => $requestData));
                die();
            }
            $message->message['token'] = $token;
        }

        $result = MessengingService::sendMessage($message);
        if ($result['statusCode'] !== 200) {
            http_response_code(401);
            echo json_encode($result['response']);
            die();
        } else {
            echo json_encode(array("status" => "success", "message" => $response));
            die();
        }
    }

    private function getSource($sourceMap)
    {
        if (!empty($sourceMap['card'])) {
            $cardData = $sourceMap['card'];
            $cardErrors = checkFields($cardData, array("cvv", "number", "pin", "expiry"));

            if ($cardErrors !== true) {
                throw new Error(
                    json_encode($cardErrors)
                );
                die();
            }

            $card = $this->cardRepo->findOne("WHERE number=?", $cardData['number']);

            if (!$card) {
                http_response_code(401);
                throw new Error(json_encode(array('status' => 'fail', 'message' => 'INVALID_CARD')));
            }

            if (
                ($card->pin != $cardData['pin'])
                || ($card->cvv != $cardData['cvv'])
                || ($card->expiry != $cardData['expiry'])
            ) {
                http_response_code(401);
                throw new Error(json_encode(array('status' => 'fail', 'message' => "CARD_DETAILS_INVALID")));
            }

            $dateTime = DateTime::createFromFormat("d/y", $card->expiry);
            if (time() >= $dateTime->getTimestamp()) {
                http_response_code(401);
                throw new Error(json_encode(array('status' => 'fail', 'message' => "CARD_EXPIRED")));
            }

            $destinationBank = $card->bank;
            $destinationAccount = $card->account;
            return array($card->bank, $card->account);
        } else {
            if (empty($sourceMap['bank'])) {
                http_response_code(400);
                echo json_encode(array('status' => 'fail', 'message' => "TRANSACTION_DETAILS_NEEDED"));
                die();
            }
            $bankData = $sourceMap['bank'];
            $bankErrors =  checkFields($bankData, array("bank", "account"));

            if ($bankErrors !== true) {
                throw new Error(json_encode($bankErrors));
            }

            return array($bankData['bank'], $bankData['account']);
        }
    }
}
