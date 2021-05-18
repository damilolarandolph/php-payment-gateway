<?php
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../services/messenging-service.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";


class AccountController
{
    private $accountRepo;

    public function __construct()
    {
        $this->accountRepo = new BankAccountRepository();
    }

    public function home()
    {
        header("Content-Type: text/html");
        require_once __DIR__ . "/../views/home.html";
    }

    public function createAccount($requestData)
    {

        $errors = checkFields($requestData, array("name", "phone"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        extract($requestData);
        $existingAccount = $this->accountRepo->findOne("WHERE phoneNumber=?", $phone);
        if ($existingAccount) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "PHONE_NUMBER_EXISTS"));
            die();
        }

        $account = new BankAccount();
        $account->fullName = $name;
        $account->phoneNumber = $phone;
        $account->balance = 0;
        $message = new Message();
        $message->to = "MASTERCARD";
        $message->from = "ZENITH";
        $message->message = array("messageType" => "CREATE_CARD", "account" => $account->accountNumber);
        $result = MessengingService::sendMessage($message);
        extract($result);
        if ($result['statusCode'] !== 200) {
            http_response_code($statusCode);
            echo $response;
            die();
        }
        $this->accountRepo->save($account);
        $resp  = array_merge(array("status" =>  "success", "account" => $account->accountNumber), $response);
        echo json_encode($resp);
    }
}
