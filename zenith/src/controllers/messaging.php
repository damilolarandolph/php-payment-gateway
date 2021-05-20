<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../middleware/jwt.php";
require_once __DIR__ . "/../services/messenging-service.php";
require_once __DIR__ . "/../services/otp-service.php";
require_once __DIR__ . "/../services/account-service.php";

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

        extract($messageParsed);
        switch ($messageType) {
            case 'WITHDRAW':
                $account = $this->bankRepo->findByAccountNumber($sourceAccount);
                if (!empty($otp)) {
                    try {
                        $result =  OtpService::verifyOtp('233' . $account->phoneNumber, $otp);
                    } catch (Error $e) {
                        http_response_code(401);
                        echo json_encode(array("status" => "fail", "message" => "INVALID_OTP_OR_EXPIRED"));
                        die();
                    }
                    if (!$result) {
                        http_response_code(401);
                        echo json_encode(array("status" => "fail", "message" => "INVALID_OTP"));
                        die();
                    }
                } else if (!empty($token)) {
                    try {
                        $result = JWTMiddleware::run($token);
                        if ($result['payload']['account'] != $account->accountNumber) {
                            http_response_code(401);
                            echo json_encode(array("status" => "fail", "message" => $result));
                            die();
                        }
                    } catch (Error $e) {
                        echo $e->getMessage();
                        die();
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(array("status" => "fail", "message" => "AUTH_NEEDED"));
                    die();
                }
                try {
                    $result = $this->accountService->withdraw($account, $amount, $destinationBank, $destinationAccount);
                } catch (Error $e) {
                    echo json_encode(array("status" => "fail", "message" => $e->getMessage()));
                    die();
                }
                if ($result) {
                    echo json_encode(array("status" => "success", "message" => "SUCCESS"));
                    die();
                } else {
                    echo json_encode(array("status" => "fail", "message" => "TRANSACTION_FAIL"));
                    die();
                }
                return json_encode(array("status" => "success"));
                break;
            case "REQUEST_OTP":
                $account = $this->bankRepo->findByAccountNumber($account);
                OtpService::sendOtp('233' . $account->phoneNumber);
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
