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
            echo $errors;
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
        $this->accountRepo->save($account);
        echo json_encode(array("status" =>  "success", "account" => $account->accountNumber));
    }
}
