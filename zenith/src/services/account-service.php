<?php
require_once __DIR__ . "/../data/repositories/account-repository.php";
require_once __DIR__ . "/messenging-service.php";

class AccountService
{
    private $accountRepo;
    public function __construct()
    {
        $this->accountRepo = new BankAccountRepository();
    }

    /**
     * @param BankAccount $account
     */
    public function withdraw($account, $amount, $toBIC, $toAccount)
    {
        if ($account->balance < $amount) {
            http_response_code(401);
            throw new Error("INSUFFICIENT_FUNDS");
        }
        if ($toBIC == "ZENITH") {
            $toAccount = $this->accountRepo->findByAccountNumber($toAccount);
            $this->deposit($toAccount, $amount);
            $this->accountRepo->update($account);
            return true;
        } else {
            $message = new Message();
            $message->to = $toBIC;
            $message->from = "ZENITH";
            $message->message = array("messageType" => "DEPOSIT", "account" => $toAccount);
            extract(MessengingService::sendMessage($message));
            if ($statusCode != 200) {
                http_response_code(401);
                return false;
            } else {
                $account->balance -= $amount;
                $this->accountRepo->update($account);
                return true;
            }
        }
    }

    public function deposit($account, $amount)
    {
        $account->balance += $amount;
        $this->accountRepo->update($account);
    }
}
