<?php

use Gateway\Data\ModelConfig;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/account.php";
require_once __DIR__ . "/../config/connection.php";

/**
 * @method BankAccount|false findById(string $id) 
 */
class BankAccountRepository extends \Gateway\Data\Repository
{
    public function __construct()
    {
        $modelConfig = new ModelConfig('accounts', BankAccount::class);
        foreach (get_class_vars($modelConfig->getClass()) as $key => $value) {
            if ($key == 'id') {
                $modelConfig->addField($key, true);
            } else {
                $modelConfig->addField($key, false);
            }
        }
        parent::__construct($modelConfig, PDOCONN::$instance);
    }


    /**
     * 
     * @param string $accountNumber  The account number
     * 
     * @return BankAccount|false Returns an instance of the BankAccount model or false
     * if an account with the account number could not be found.
     * 
     */
    public function findByAccountNumber($accountNumber)
    {
        $bankAccount = $this->findOne("WHERE accountNumber = ?", $accountNumber);
        if (!$bankAccount) {
            return false;
        }
        return $bankAccount;
    }
}
