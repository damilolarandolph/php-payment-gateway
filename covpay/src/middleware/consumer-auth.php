<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";


class ConsumerAuthMiddleware
{
    public static function invokeHeaderAuth()
    {

        $headers = getallheaders();
        if (empty($headers['authorization'])) {
            http_response_code(401);
            throw new Error(
                "AUTH_HEADER_MISSING"
            );
        }
        $authHeader = $headers['authorization'];
        $consumerRepo = new ConsumerRepository();

        $consumer = $consumerRepo->findById($authHeader);

        if (!$consumer) {
            http_response_code(401);
            throw new Error(
                "INVALID_CONSUMER"
            );
        }

        return $consumer;
    }
}
