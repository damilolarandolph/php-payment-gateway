<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../../../common/utils/aes-encrypt.php";

class MessengingController
{

    private $consumerRepo;

    public function __construct()
    {
        $this->consumerRepo = new ConsumerRepository();
    }

    public function  receiveMessage($reqeustData)
    {
        $errors = checkFields($reqeustData, array("to", "from", "message"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        extract($reqeustData);

        $toConsumer = $this->consumerRepo->findOne("WHERE name=?", $to);
        if (!$toConsumer) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "INVALID_RECEIPIENT"));
            die();
        }

        $fromConsumer = $this->consumerRepo->findOne("WHERE name=?", $from);
        if (!$fromConsumer) {
            http_response_code(401);
            echo json_encode(array("status" => "fail", "message" => "INVALID_SENDER"));
            die();
        }

        $messageDecrypted = AESEncryptionEngine::decrypt($message, $fromConsumer->secret);
        $json = json_decode($messageDecrypted, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("status" => "fail", "message" => "INVALID_MESSAGE"));
            die();
        }

        $messageRepackaged = AESEncryptionEngine::encrypt(json_encode($json), $toConsumer->secret);

        $fields = [
            'to' => $to,
            'from' => $from,
            'message' => $messageRepackaged,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $toConsumer->messengingEndpoint,
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($fields),
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        http_response_code($http_code);
        echo $response;
    }
}
