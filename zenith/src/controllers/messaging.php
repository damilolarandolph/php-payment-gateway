<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../middleware/jwt.php";
require_once __DIR__ . "/../services/messenging-service.php";
require_once __DIR__ . "/../services/otp-service.php";

class MessagingController
{

    private $bankRepo;
    private $accountService;


    public function __construct()
    {
        $this->bankRepo = new BankAccountRepository();
        $this->accountService = new AccountService();
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
            case 'WITHDRAW':
                $account = $this->bankRepo->findByAccountNumber($sourceAccount);
                if (!empty($otp)) {
                    $result =  OtpService::verifyOtp($account->phoneNumber, $otp);
                    if (!$result) {
                        http_response_code(401);
                        echo json_encode(array("status" => "fail", "message" => "INVALID_OTP"));
                        die();
                    }
                } else if (!empty($token)) {
                    try {
                        $result = JWTMiddleware::run($token);
                        if (!$result['account'] != $account->accountNumber) {
                            http_response_code(401);
                            echo json_encode(array("status" => "fail", "message" => "ACCOUNT_MISMATCH"));
                            die();
                        }
                    } catch (Error $e) {
                        echo $e->getMessage();
                        die();
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(array("status" => "fail", "message" => "AUTH_NEEDED"));
                }

                $result = $this->accountService->withdraw($account, $amount, $destinationBank, $destinationAccount);
                if ($result) {
                    echo json_encode(array("status" => "success"));
                    die();
                } else {
                    echo json_encode(array("status" => "fail", "message" => "TRANSACTION_FAIL"));
                    die();
                }
                break;
            case "REQUEST_OTP":
                $account = $this->bankRepo->findByAccountNumber($account);
                OtpService::sendOtp($account);
                echo json_encode(array("status" => "success"));
                die();
                break;
            case "DEPOSIT":
                $account = $this->bankRepo->findByAccountNumber($account);
                $this->accountService->deposit($account, $amount);
                echo json_encode(array("status" => "success"));
                die();
                break;
        }
    }
}
