<?php
require_once __DIR__ . "/../../../common/utils/aes-encrypt.php";



abstract class MessengingService
{

    /**
     * @param Message $message
     */
    public static function sendMessage($message)
    {
        $key = constant("MESSENGING_KEY");
        $message->message = AESEncryptionEngine::encrypt(json_encode($message->message), $key);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "banknet.com/message",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
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
        return array('response' => json_decode($response, true), 'statusCode' => $http_code);
    }

    /**
     * 
     * @param string $messagePayloadCipher
     * 
     * @return array[]|boolean
     */
    public static function extractMessage($messagePayloadCipher)
    {
        $key = constant("MESSENGING_KEY");
        $messageDecrypted = AESEncryptionEngine::decrypt($messagePayloadCipher, $key);
        if (!$messageDecrypted) {
            http_response_code(401);
            return false;
        }
        $json = json_decode($messageDecrypted, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(401);
            return false;
        }

        return $json;
    }
}

class Message
{
    public $to;
    public $from;
    public $message;
}
