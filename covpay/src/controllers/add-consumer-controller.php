<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../../../common/utils/random_int.php";

class AddConsumerController
{

    /** @var ConsumerRepository */
    private $consumerRepo;
    public function __construct()
    {
        $this->consumerRepo = new ConsumerRepository();
    }
    public function show()
    {
        require_once __DIR__ . "/../views/add-consumer.php";
    }
    public function createConsumer($requestData)
    {
        $errors = checkFields($requestData, array("name", "bank", "account"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $base64Data = base64_encode(json_encode($requestData));
        $message = array(
            "redirectURL" => "http://covpay.com/api/consumer/continue",
            "account" => $requestData['account'],
            "data" => $base64Data
        );
        $curl = curl_init();
        $key = constant("ZENITH_KEY");
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://zenith.com/oauth/authorize",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$key}"),
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

    public function continueCreate($requestData)
    {
        $errors = checkFields($requestData, array("status", "data", "accessCode"));

        if (!$errors) {
            echo json_encode($errors);
            die();
        }
        extract($requestData);
        if ($status == 'fail') {
            http_response_code(401);
            header("Content-Type: text/html");
            echo "<h1>Failed to authorize, {$message}</h1>";
            die();
        }

        $dataParsed = json_decode(base64_decode($data), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            header("Content-Type: text/html");
            echo "<h1>Invalid Data</h1>";
            die();
        }
        $curl = curl_init();
        $key = constant("ZENITH_KEY");
        $secret = constant("ZENITH_SECRET");
        $nonce = getRandomInts(0, 9, 10);
        $challenge = hash_hmac("sha256", $nonce, hex2bin($secret));
        $message = array(
            "nonce" => $nonce,
            "challenge" => $challenge,
            "accessCode" => $requestData['accessCode']
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://zenith.com/oauth/swap",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "authorization: {$key}"),
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
            http_response_code(401);
            header("Content-Type: text/html");
            echo "<h1>Swap Failed</h1>";
            die();
        }
        $respObj = json_decode($response, true);
        $consumer = new Consumer();
        $consumer->bankAccount = $dataParsed['account'];
        $consumer->bankBIC = $dataParsed['bank'];
        $consumer->token = $respObj['accessToken'];
        $consumer->refreshToken = $respObj['refreshToken'];
        $consumer->name = $dataParsed['name'];
        $this->consumerRepo->save($consumer);
        $consumer = $this->consumerRepo->findOne("WHERE apiSecret=?", $consumer->apiSecret);
        header("Content-Type: text/html");
        echo "<h1>Api Key: {$consumer->apiKey}</h1><br /><h1>Api Secret: {$consumer->apiSecret}</h1>";
    }
}
