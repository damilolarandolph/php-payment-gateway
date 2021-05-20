<?php

use Gateway\Util\JWT;

require_once __DIR__ . "/../data/repositories/payment-repo.php";
require_once __DIR__ . "/../../../common/utils/jwt.php";

abstract class JWTMiddleware
{

    static function checkToken($token)
    {


        $paymentRepo = new PaymentRepository();

        $claims = null;
        $key = constant("JWT_KEY");
        try {
            $claims = JWT::getClaims($token);
        } catch (Error $e) {
            http_response_code(400);
            throw new Error("TOKEN_MALFORMED");
        }

        if (!JWT::verifyToken($claims, $key)) {
            http_response_code(401);
            throw new Error("INVALID_TOKEN");
        }

        $payment = $paymentRepo->findById($claims['payload']['paymentId']);

        if ($payment->state !== PaymentStates::PENDING) {
            http_response_code(401);
            throw new Error("PAYMENT_ALREADY_PROCESSED");
        }

        if (intval($claims['payload']['exp']) <= time()) {
            http_response_code(401);
            throw new Error("TOKEN_EXPIRED");
        }





        return $claims;
    }
}
