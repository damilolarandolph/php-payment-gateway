<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../../../common/utils/jwt.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../data/repositories/payment-repo.php";
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../data/repositories/card-details-repo.php";
require_once __DIR__ . "/../middleware/consumer-auth.php";
require_once __DIR__ . "/../middleware/jwt.php";
require_once __DIR__ . "/../../../common/utils/aes-encrypt.php";
require_once __DIR__ . "/../../../common/utils/random_int.php";


class PaymentController
{
    private $paymentRepo;
    private $consumerRepo;
    private $cardDetails;

    public function __construct()
    {
        $this->paymentRepo = new PaymentRepository();
        $this->consumerRepo = new ConsumerRepository();
        $this->cardDetails = new CardDetailsRepository();
    }

    public function create($requestData)
    {
        $consumer = null;

        try {
            $consumer = ConsumerAuthMiddleware::invokeHeaderAuth();
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }

        $errors = checkFields(
            $requestData,
            array(
                "payerPhone",
                "payerName",
                "amount"
            )
        );

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        extract($requestData);
        $jwtKey = constant("JWT_KEY");
        $payment = new Payment();
        $payment->payerPhone = $payerPhone;
        $payment->payerName = $payerName;
        $payment->data = $data ?? "";
        $payment->amount = $amount;
        $payment->consumerId = $consumer->apiKey;
        $this->paymentRepo->save($payment);
        $jwt = JWT::createToken(
            time() + (60 * 15),
            "COVPAY",
            array(
                "consumerId" => $consumer->apiKey,
                "paymentId" => $payment->id,
            ),
            $jwtKey
        );

        echo json_encode(array(
            "status" => "success",
            "redirectURL" => "http://covpay.com/checkout?token={$jwt}",
            "paymentId" => $payment->id
        ));
    }

    public function checkout($requestData)
    {
        $errors = checkFields($requestData, array("cardNumber", "pin", "cvv", "expiry"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $claims = null;
        try {
            $claims = JWTMiddleware::checkToken($requestData["token"]);
        } catch (Error $e) {
            echo json_encode(array("status" => "fail", "message" => $e->getMessage()));
            die();
        }
    }

    public function getCheckoutInfo($requestData)
    {
        $errors = checkFields($requestData, array("token"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $claims = null;
        try {
            $claims = JWTMiddleware::checkToken($requestData["token"]);
        } catch (Error $e) {
            echo json_encode(array("status" => "fail", "message" => $e->getMessage()));
            die();
        }

        $payment = $this->paymentRepo->findById($claims['payload']['paymentId']);
        $consumer = $this->consumerRepo->findById($claims['payload']['consumerId']);
        $message = array(
            "status" => "success",
            "payerName" => $payment->payerName,
            "amount" => $payment->amount,
            "consumerName" => $consumer->name,
            "publicKey" => $consumer->publicKey
        );

        echo json_encode($message);
    }

    public function setCardDetails($requestData)
    {
        $errors = checkFields($requestData, array("cardNumber", "pin", "cvv", "expiry"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $claims = null;
        try {
            $claims = JWTMiddleware::checkToken($requestData["token"]);
        } catch (Error $e) {
            echo json_encode(array("status" => "fail", "message" => $e->getMessage()));
            die();
        }
        $consumer = $this->consumerRepo->findById($claims['payload']['consumerId']);
        $payment = $this->paymentRepo->findById($claims['payload']['paymentId']);

        $cardNumber = null;

        $cvv = null;

        $mastercardKey = constant('MASTERCARD_KEY');
        $mastercardSecret = constant("MASTERCARD_SECRET");

        // if (!openssl_private_decrypt(
        //     $requestData['cardNumber'],
        //     $cardNumber,
        //     $consumer->privateKey
        // )) {
        //     http_response_code(401);
        //     echo json_encode(array(
        //         "status" => "fail",
        //         "message" => "FAILED_DECRYPTION"
        //     ));
        //     die();
        // }
        // if (!openssl_private_decrypt(
        //     $requestData['cvv'],
        //     $cvv,
        //     $consumer->privateKey
        // )) {
        //     http_response_code(401);
        //     echo json_encode(array(
        //         "status" => "fail",
        //         "message" => "FAILED_DECRYPTION"
        //     ));
        //     die();
        // }

        $key = constant("JWT_KEY");
        $card = new CardDetails();
        $card->pin = $requestData['pin'];
        $card->cardNumber = $requestData['cardNumber'];
        $card->cvv = $requestData['cvv'];
        $card->expiry = $requestData['expiry'];
        $this->cardDetails->save($card);
        $payment->cardDetailsId = $card->id;
        $this->paymentRepo->update($payment);
        $nonce = getRandomInts(0, 9, 10);
        $message = array(
            "card" => array(
                "cvv" => $requestData['cvv'],
                "number" => $requestData['cardNumber'],
                "pin" => $requestData['pin'],
                "expiry" => $requestData["expiry"],
            ),

            "nonce" => $nonce,
            "challenge" => hash_hmac("sha256", $nonce, hex2bin(constant("MASTERCARD_SECRET")))
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mastercard.com/api/requestotp",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$mastercardKey}"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($message),
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        http_response_code($http_code);
        echo $response;
    }

    public function confirmOtp($requestData)
    {
        $errors = checkFields($requestData, array("token", "answer"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        $claims = null;
        try {
            $claims = JWTMiddleware::checkToken($requestData["token"]);
        } catch (Error $e) {
            echo json_encode(array("status" => "fail", "message" => $e->getMessage()));
            die();
        }
        $consumer = $this->consumerRepo->findById($claims['payload']['consumerId']);
        $payment = $this->paymentRepo->findById($claims['payload']['paymentId']);
        /**
         * @var CardDetails $cardDetails
         */
        $cardDetails = $this->cardDetails->findById($payment->cardDetailsId);
        $nonce = getRandomInts(0, 9, 10);
        $mastercardKey = constant('MASTERCARD_KEY');
        $mastercardSecret = constant("MASTERCARD_SECRET");
        $message = array(
            "sourceOfFunds" => array(
                "card" => array(
                    "cvv" => $cardDetails->cvv,
                    "number" => $cardDetails->cardNumber,
                    "pin" => $cardDetails->pin,
                    "expiry" => $cardDetails->expiry,
                )
            ),

            "destination" => array(
                'bank' => array(
                    'bank' => $consumer->bankBIC,
                    'account' => $consumer->bankAccount
                )
            ),

            "nonce" => $nonce,
            "challenge" => hash_hmac("sha256", $nonce, hex2bin($mastercardSecret)),
            "amount" => $payment->amount,
            'otp' => $requestData['answer']
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mastercard.com/api/pay",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$mastercardKey}"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($message),
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        http_response_code($http_code);
        if ($http_code == 200) {
            $payment->state = PaymentStates::SUCCESS;
        } else {
            $payment->state = PaymentStates::FAILED;
        }
        $this->paymentRepo->update($payment);
        echo $response;
    }


    public function refundTransaction($requestData)
    {
        $consumer = null;
        try {
            $consumer = ConsumerAuthMiddleware::invokeHeaderAuth();
        } catch (Error $e) {
            echo json_encode(array("status" => "success", "message" => $e->getMessage()));
            die();
        }

        $errors = checkFields($requestData, array("paymentId"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $payment = $this->paymentRepo->findById($requestData['paymentId']);

        if (!$payment) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "PAYMENT_NOT_VALID"));
            die();
        }

        if ($payment->consumerId != $consumer->apiKey) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "PAYMENT_ACCESS_UNAUTHORIZED"));
            die();
        }

        if ($payment->state !== PaymentStates::SUCCESS) {
            http_response_code(401);
            echo json_encode(array("status" => 'fail', "message" => "PAYMENT_NOT_SUCCESS"));
            die();
        }

        $message = array(
            "refreshToken" => $consumer->refreshToken
        );
        $curl = curl_init();
        $zenithKey = constant("ZENITH_KEY");
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://zenith.com/oauth/refresh",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$zenithKey}"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($message),
        ));
        $response1 = curl_exec($curl);
        $http_code1 = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        if ($http_code1 !== 200) {
            http_response_code($http_code1);
            echo $response1;
            die();
        }
        $responseObj = json_decode($response1, true);
        $payment->refreshToken = $responseObj['refreshToken'];
        $payment->token = $responseObj['accessToken'];

        /**
         * @var CardDetails $cardDetails
         */
        $cardDetails = $this->cardDetails->findById($payment->cardDetailsId);
        $nonce = getRandomInts(0, 9, 10);
        $mastercardKey = constant('MASTERCARD_KEY');
        $mastercardSecret = constant("MASTERCARD_SECRET");
        $message = array(
            "destination" => array(
                "card" => array(
                    "cvv" => $cardDetails->cvv,
                    "number" => $cardDetails->cardNumber,
                    "pin" => $cardDetails->pin,
                    "expiry" => $cardDetails->expiry,
                )
            ),

            "sourceOfFunds" => array(
                'bank' => array(
                    'bank' => $consumer->bankBIC,
                    'account' => $consumer->bankAccount
                )
            ),

            "nonce" => $nonce,
            "challenge" => hash_hmac("sha256", $nonce, hex2bin($mastercardSecret)),
            "amount" => $payment->amount,
            'token' => $payment->token
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mastercard.com/api/pay",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$mastercardKey}"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($message),
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        if ($http_code !== 200) {
            http_response_code($http_code);
            echo $response;
            die();
        }
        $payment->state  = PaymentStates::REFUNDED;
        $this->paymentRepo->update($payment);
        echo json_encode(array("status" => "success"));
    }


    public function getTransactions()
    {

        $consumer = null;

        try {
            $consumer = ConsumerAuthMiddleware::invokeHeaderAuth();
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }

        $payments = $this->paymentRepo->find("WHERE consumerId=?", $consumer->apiKey);
        echo json_encode($payments);
    }

    public function getTransactionsForPayer($requestData)
    {
        $consumer = null;

        try {
            $consumer = ConsumerAuthMiddleware::invokeHeaderAuth();
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        $errors = checkFields($requestData, array("payerPhone"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $payments = $this->paymentRepo->find("WHERE consumerId=? AND payerPhone=?", $consumer->apiKey, $requestData['payerPhone']);
        echo json_encode($payments);
    }

    public function getTransaction($requestData)
    {
        $consumer = null;
        try {
            $consumer = ConsumerAuthMiddleware::invokeHeaderAuth();
        } catch (Error $e) {
            echo $e->getMessage();
            die();
        }
        $errors = checkFields($requestData, array("paymentId"));
        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        $payment = $this->paymentRepo->findOne("WHERE id=? AND consumerId=?", $requestData['paymentId'], $consumer->apiKey);

        if (!$payment) {
            http_response_code(404);
            echo json_encode(array("status" => "fail", "message" => "PAYMENT_NOT_FOUND"));
            die();
        }

        echo json_encode(array("status" => "success", "payment" => $payment));
    }
}
