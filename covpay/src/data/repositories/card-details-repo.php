<?php


use Gateway\Data\ModelConfig;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/card-details.php";
require_once __DIR__ . "/../config/connection.php";

/**
 * @method CardDetails findById(string $id) 
 */
class CardDetailsRepository extends \Gateway\Data\Repository
{
    public function __construct()
    {
        global $dbConn;
        $modelConfig = new ModelConfig('cardDetails', CardDetails::class);
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
