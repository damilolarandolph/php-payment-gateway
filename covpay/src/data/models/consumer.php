<?php
require_once __DIR__ . "/../../../../common/utils/random_256.php";


class Consumer
{
    public $apiKey;
    public $apiSecret;
    public $bankAccount;
    public $bankBIC;
    public $token;
    public $refreshToken;
    public $name;
    public $privateKey;
    public $publicKey;

    public function __construct()
    {
        $this->apiSecret  =  random256Hex();
        $res = openssl_pkey_new(array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type"  => OPENSSL_KEYTYPE_RSA
        ));
        openssl_pkey_export($res, $privateKey);
        $this->privateKey = $privateKey;
        $this->publicKey = (openssl_pkey_get_details($res))["key"];
    }
}
