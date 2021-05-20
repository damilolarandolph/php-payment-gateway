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


        $revoked = $revokedTokenRepo->findOne("WHERE token=?", $token);
        if ($revoked) {
            http_response_code(401);
            throw new Error("TOKEN_REVOKED");
        }

        $claims = null;
        try {
            $claims = JWT::getClaims($token);
        } catch (Error $e) {
            http_response_code(400);
            throw new Error("TOKEN_MALFORMED");
        }

        if ($claims['payload']['exp'] <= time()) {
            http_response_code(401);
            throw new Error("TOKEN_EXPIRED");
        }

        $account = $accountRepo->findByAccountNumber($claims['payload']['account']);
        if (!$account) {
            http_response_code(401);
            throw new Error("INVALID_TOKEN_ACCOUNT");
        }

        if (!JWT::verifyToken($claims, $account->signingKey)) {
            http_response_code(401);
            throw new Error("INVALID_TOKEN_SIGNATURE");
        }

        return $claims;
    }
}
