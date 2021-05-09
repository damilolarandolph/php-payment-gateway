<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../data/repositories/consumer-repository.php";
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/../services/otp-service.php";




class OauthController
{


  private $consumerRepo;
  private $accountRepo;

  public function __construct()
  {
    $this->consumerRepo = new ConsumerRepository();
    $this->accountRepo = new BankAccountRepository();
  }


  
  public function authorize($requestData)
  {
    require_once __DIR__ . '/../middleware/oauth-auth-stage-1.php';
    checkFields($requestData, array('redirectURL', 'account'));
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

    checkFields($_GET, array('consumerId', 'account', 'redirectURL'));
    $consumerId = $_GET['consumerId'];
    $data = empty($_GET['data']) ? '' : $_GET['data'];
    $account = $_GET['account'];
    $redirectURL = $_GET['redirectURL'];
    $accountObj = $this->accountRepo->findByAccountNumber($account);
    if (!$accountObj) {
      http_response_code(401);
      header("Location: $redirectURL?status=fail&message=INVALID_ACCOUNT&data=$data");
      die();
    }
    // OtpService::sendOtp($account->phoneNumber);
    header('Content-Type: text/html');
    require_once __DIR__ . "/../views/confirm-otp.php";
  }

  public function confirmOtp($requestData)
  {
  }
}
