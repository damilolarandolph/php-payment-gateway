<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../data/repositories/token-repository.php";
require_once __DIR__ . "/../data/repositories/revoked-token-repository.php";
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../../../common/utils/jwt.php";

abstract class JWTMiddleware
{

    static function run($token)
    {

        $revokedTokenRepo = new RevokedTokenRepository();
        $accountRepo = new BankAccountRepository();


        $token = $revokedTokenRepo->findOne("WHERE token=?", $token);
        if ($token) {
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
