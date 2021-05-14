<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";

$headers = getallheaders();
if (empty($headers['authorization'])) {
    http_response_code(401);
    $message = new \stdClass();
    $message->message = "AUTH_HEADER_MISSING";
    echo json_encode($message);
    die();
}
$authHeader = $headers['authorization'];
$consumerRepo = new ConsumerRepository();

$consumer = $consumerRepo->findById($authHeader);

if (!$consumer) {
    http_response_code(401);
    echo json_encode(array('message' => "INVALID_CONSUMER"));
    die();
}
