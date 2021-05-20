<?php


use Gateway\Data\ModelConfig;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/payment.php";
require_once __DIR__ . "/../config/connection.php";

/**
 * @method Consumer findById(string $id) 
 */
class PaymentRepository extends \Gateway\Data\Repository
{
    public function __construct()
    {
        global $dbConn;
        $modelConfig = new ModelConfig('payments', Payment::class);
        foreach (get_class_vars($modelConfig->getClass()) as $key => $value) {
            if ($key == 'id') {
                $modelConfig->addField($key, true);
            } else {
                $modelConfig->addField($key, false);
            }
        }
        parent::__construct($modelConfig, $dbConn);
    }
}
