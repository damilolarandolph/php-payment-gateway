<?php


abstract class AESEncryptionEngine
{

    public static function encrypt($plainText, $key)
    {
        $ivLength = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($ivLength);
        $cipherText = openssl_encrypt($plainText, $cipher, hex2bin($key), $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac("sha256", $cipherText, hex2bin($key), $as_binary = true);
        return base64_encode($iv . $hmac . $cipherText);
    }

    public static function decrypt($cipherText, $key)
    {
        $rawCipher = base64_decode($cipherText);
        if (!$rawCipher) {
            return false;
        }

        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = substr($rawCipher, 0, $ivlen);
        $hmac = substr($rawCipher, $ivlen, $sha2len = 32);
        $cipherTextRaw = substr($rawCipher, $ivlen + $sha2len);
        $originalPlaintext = openssl_decrypt($cipherTextRaw, $cipher, hex2bin($key), $options = OPENSSL_RAW_DATA, $iv);
        if (!$originalPlaintext) {
            return false;
        }
        $calcmac = hash_hmac('sha256', $cipherTextRaw, hex2bin($key), $asBinary = true);

        if (hash_equals($hmac, $calcmac)) {
            return $originalPlaintext;
        }

        return false;
    }
}
