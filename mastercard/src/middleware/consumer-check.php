<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";


abstract class ConsumerCheckMiddleware
{

    public static function invoke($requestData)
    {
        $consumerRepo = new ConsumerRepository();
        $errors = checkFields($requestData, array("nonce", "challenge"));

        $nonce = $requestData['nonce'];
        $challenge = $requestData['challenge'];
        if ($errors !== true) {
            throw new Error(json_encode($errors));
        }
        $headers = getallheaders();
        if (empty($headers['authorization'])) {
            http_response_code(401);
            $message = new \stdClass();
            $message->status = 'fail';
            $message->message = "AUTH_HEADER_MISSING";
            throw new Error(json_encode($message));
        }
        $authHeader = $headers['authorization'];
        $consumer = $consumerRepo->findById($authHeader);

        if (!$consumer) {
            http_response_code(401);
            throw new Error(json_encode(array("status" => "fail", "message" => "INVALID_CONSUMER")));
        }

        $calcHash = hash_hmac('sha256', $nonce, hex2bin($consumer->apiSecret));

        if (!hash_equals($calcHash, $challenge)) {
            http_response_code(401);
            throw new Error(json_encode(array("status" => "fail", "message" => "INVALID_CHALLENGE")));
        }

        return $consumer;
    }
}
