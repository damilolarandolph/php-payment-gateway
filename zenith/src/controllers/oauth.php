<?php

use Gateway\Util\JWT;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../data/repositories/token-repository.php";
require_once __DIR__ . "/../services/otp-service.php";
require_once __DIR__ . "/../../../common/utils/jwt.php";
require_once __DIR__ . "/../../../common/utils/random_int.php";




class OauthController
{


  private $consumerRepo;
  private $accountRepo;
  private $tokenRepo;

  public function __construct()
  {
    $this->consumerRepo = new ConsumerRepository();
    $this->accountRepo = new BankAccountRepository();
    $this->tokenRepo = new TokenRepository();
  }



  public function authorize($requestData)
  {
    require_once __DIR__ . '/../middleware/oauth-auth-stage-1.php';
    $errors = checkFields($requestData, array('redirectURL', 'account'));
    if ($errors !== true) {
      http_response_code(400);
      echo json_encode($errors);
      die();
    }
    $consumerId = $consumer->apiKey;
    $data = empty($requestData['data']) ? '' : $requestData['data'];
    $account = $requestData['account'];
    $redirectURL = $requestData['redirectURL'];
    $url = "http://zenith.com/oauth/dialog?account=$account&redirectURL=$redirectURL&data=$data&consumerId=$consumerId";
    if (!$this->accountRepo->findByAccountNumber($account)) {
      http_response_code(401);
      echo json_encode(array("status" => "failed", "message" => "INVALID_ACCOUNT"));
      die();
    }
    echo json_encode(array(
      'redirectURL' => $url,
      'status' => 'success'
    ));
    return;
  }




  public function displayOtpConfirm()
  {

    $errors =   checkFields($_GET, array('consumerId', 'account', 'redirectURL'));
    $redirectURL = $_GET['redirectURL'];
    $data = empty($_GET['data']) ? '' : $_GET['data'];
    if ($errors !== true) {
      http_response_code(400);
      header("Location: $redirectURL?status=fail&message={$errors['code']}&data=$data");
    }
    $consumerId = $_GET['consumerId'];
    $account = $_GET['account'];
    $accountObj = $this->accountRepo->findByAccountNumber($account);
    if (!$accountObj) {
      http_response_code(401);
      header("Location: $redirectURL?status=fail&message=INVALID_ACCOUNT&data=$data");
      die();
    }
    OtpService::sendOtp('233' . $accountObj->phoneNumber);
    header('Content-Type: text/html');
    require_once __DIR__ . "/../views/confirm-otp.php";
  }

  public function confirmOtp($requestData)
  {
    $errors =  checkFields($requestData, array('consumerId', 'otpAnswer', 'account', 'redirectURL'));
    $redirectURL = $requestData['redirectURL'];
    $data = empty($requestData['data']) ? '' : $requestData['data'];
    if ($errors !== true) {
      http_response_code(400);
      header("Location: $redirectURL?status=fail&message={$errors['code']}_{$errors['field']}&data=$data");
    }
    extract($requestData);
    $accountObj = $this->accountRepo->findByAccountNumber($account);
    if (!$accountObj) {
      http_response_code(401);
      header("Location: $redirectURL?status=fail&message=INVALID_ACCOUNT&data=$data");
      die();
    }

    try {
      $result = OtpService::verifyOtp($accountObj->phoneNumber, $otpAnswer);

      if ($result) {
        $accessToken =  JWT::createToken(time() + (60 * 60), "COVPAYZEN", array(
          'account' => $accountObj->accountNumber,
          'consumerId' => $consumerId,
        ), $accountObj->signingKey);
        $refreshToken = JWT::createToken(time() + (30 * 24 * 60 * 60), "COVPAYZEN", array(
          'account' => $accountObj->accountNumber,
          'consumerId' => $consumerId,
          'accessToken' => $accessToken,
        ), $accountObj->signingKey);

        $tokenRepo = new TokenRepository();
        $token = new Token();
        $token->token = $accessToken;
        $token->refreshToken = $refreshToken;
        $token->consumerId = $consumerId;
        $tokenRepo->save($token);
        $token = $tokenRepo->findOne("WHERE token=? AND refreshToken=?", $accessToken, $refreshToken);
        header("Location: $redirectURL?status=success&data=$data&accessCode={$token->id}");
      } else {
        header("Location: $redirectURL?status=fail&message=OTP_FAILED&data=$data");
      }
    } catch (Exception $e) {
      header("Location: $redirectURL?status=fail&message=OTP_ERROR&data=$data");
    }
  }

  public function swapToken($requestData)
  {
    require_once __DIR__ . "/../middleware/oauth-auth-stage-1.php";
    $errors = checkFields($requestData, array('nonce', 'challenge', 'accessCode'));
    if ($errors !== true) {
      echo json_encode($errors);
      die();
    }
    extract($requestData);
    $token =  $this->tokenRepo->findById($accessCode);
    if ($token->consumerId !== $consumer->apiKey) {
      http_response_code(401);
      echo json_encode(array('status' => 'fail', 'message' => "CONSUMER_MISMATCH"));
      die();
    }

    $computedChallenge = hash_hmac('sha256', $nonce, hex2bin($consumer->apiSecret));

    if ($computedChallenge != $challenge) {
      http_response_code(401);
      echo json_encode(array('status' => 'fail', 'message' => "INVALID_CHALLENGE"));
      die();
    }

    echo json_encode(array(
      'status' => 'success',
      'accessToken' => $token->token,
      'refreshToken' => $token->refreshToken
    ));
  }
}
