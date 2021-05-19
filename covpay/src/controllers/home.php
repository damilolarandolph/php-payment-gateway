<?php
require_once __DIR__ . "/../../../common/utils/field_checker.php";

class HomeController
{

    public function home()
    {
        require_once __DIR__ . "/../views/home.html";
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
}
