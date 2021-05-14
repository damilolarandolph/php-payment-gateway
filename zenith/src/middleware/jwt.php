<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../data/repositories/token-repository.php";
require_once __DIR__ . "/../data/repositories/revoked-token-repository.php";
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../../../common/utils/jwt.php";

abstract class JWTMiddleware
{

    static function run()
    {

        $revokedTokenRepo = new RevokedTokenRepository();
        $accountRepo = new BankAccountRepository();

        $headers = getallheaders();
        if (empty($headers['authorization'])) {
            http_response_code(401);
            $message = new \stdClass();
            $message->message = "AUTH_HEADER_MISSING";
            throw new Error("AUTH_HEADER_MISSING");
        }

        $authHeader  = $headers['authorization'];
        $authHeaderParts = explode(' ', $authHeader);
        if (count($authHeaderParts) !== 2) {
            http_response_code(400);
            throw new Error("AUTH_HEADER_WRONG_LENGTH");
        }

        list($bearer, $token) = $authHeaderParts;

        if ($bearer !== 'Bearer') {
            http_response_code(400);
            throw new Error("BEARER_MISSING");
        }

        $token = $revokedTokenRepo->findOne("WHERE token=?", $token);
        if (!$token) {
            http_response_code(401);
            throw new Error("TOKEN_REVOKED");
        }

        $claims = null;
        try {
            $claims =   JWT::getClaims($token);
        } catch (Error $e) {
            http_response_code(400);
            throw new Error("TOKEN_MALFORMED");
        }

        if ($claims['exp'] >= time()) {
            http_response_code(401);
            throw new Error("TOKEN_EXPIRED");
        }

        $account = $accountRepo->findByAccountNumber($claims['account']);
        if (!$account) {
            http_response_code(401);
            throw new Error("INVALID_TOKEN");
        }

        if (!JWT::verifyToken($claims, $account->signingKey)) {
            http_response_code(401);
            throw new Error("INVALID_TOKEN");
        }

        return $claims;
    }
}
