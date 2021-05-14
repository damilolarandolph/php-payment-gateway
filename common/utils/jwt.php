<?php

namespace Gateway\Util;

use Gateway\Exception\MalFormedTokenString;

require_once __DIR__ . '/../exceptions/jwt-exceptions.php';

abstract class JWT
{
    public static function verifyToken($tokenClaims, $signingKey)
    {
        $header64 = base64_encode(json_encode($tokenClaims['header']));
        $payload64 = base64_encode(json_encode($tokenClaims['payload']));
        $computedSignature = hash_hmac('sha256', $header64 . '.' . $payload64, hex2bin($signingKey));
        return $computedSignature == $tokenClaims['signature'];
    }
    public static function createToken($exp, $iss, $otherClaims, $signingKey)
    {
        $header = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array_merge(array('exp' => $exp, 'iss' => $iss), $otherClaims);
        $headerPayload = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $headerPayload, hex2bin($signingKey));
        return $headerPayload . '.' . $signature;
    }
    public static function getClaims($token)
    {
        $tokenParts = explode('.', $token);
        $length = count($tokenParts);
        if (count($tokenParts) != 3) {
            throw new MalFormedTokenString("JWT should have 3 parts, only {$length} found");
        }
        list($header, $payload, $signature) = $tokenParts;
        $headerObj = json_decode(base64_decode($header), true);
        $payloadObj = json_decode(base64_decode($payload), true);

        if (!$headerObj) {
            throw new MalFormedTokenString("JWT header is malformed");
        }

        if (!$payloadObj) {
            throw new MalFormedTokenString("JWT payload is malformed");
        }

        return array('header' => $headerObj, 'payload' => $payloadObj, 'signature' => $signature);
    }
}
