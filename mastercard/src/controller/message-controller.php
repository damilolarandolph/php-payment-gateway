<?php

require_once __DIR__ . "/../data/repositories/card-repository.php";
require_once __DIR__ . "/../services/messenging-service.php";

class MessageController
{

    private $cardRepo;

    public function __construct()
    {
        $this->cardRepo = new CardRepository();
    }

    public function message($requestData)
    {
        $messageParsed = MessengingService::extractMessage($requestData['message']);
        if (!$messageParsed) {
            echo json_encode(array("status" => "fail", "message" => "INVALID_MESSAGE"));
            die();
        }

        $messageType = $messageParsed['messageType'];

        extract($messageParsed);
        switch ($messageType) {
            case 'CREATE_CARD':
                $account = $messageParsed['account'];
                $card = new Card();
                $card->bank = $requestData['from'];
                $card->account = $account;
                $this->cardRepo->save($card);
                echo json_encode(array(
                    "status" => "success",
                    "number" => $card->number,
                    "pin" => $card->pin,
                    "cvv" => $card->cvv,
                    "expiry" => $card->expiry
                ));
                die();
                break;
        }
    }
}
