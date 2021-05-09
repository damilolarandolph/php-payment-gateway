<?php


abstract class OtpService
{


    public static function sendOtp($phoneNumber)
    {
        $fields = [
            'expiry' => 5,
            'length' => 6,
            'medium' => 'sms',
            'message' => 'Your Bank OTP is, %otp_code%',
            'number' => $phoneNumber,
            'sender_id' => 'COVPAYZEN',
            'type' => 'numeric',
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sms.arkesel.com/api/otp/generate',
            CURLOPT_HTTPHEADER => array('api-key: ' . constant('ARKESEL_KEY')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $fields,
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
    }

    public static function verifyOtp($phoneNumber, $answer)
    {
        $fields = [
            'api_key' => constant('ARKESEL_KEY'),
            'code' => $answer,
            'number' => $phoneNumber,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sms.arkesel.com/api/otp/verify',
            CURLOPT_HTTPHEADER => array('api-key: ' . constant('ARKESEL_KEY')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $fields,
        ));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        $respObj = json_decode($response);

        if ($respObj->code == "1100" && $http_code == 200) {
            return true;
        } else if ($respObj->code == "1104" && $http_code == 200) {
            return false;
        }

        throw new Error("Arkesel otp failed $response http code $http_code");
    }
}
