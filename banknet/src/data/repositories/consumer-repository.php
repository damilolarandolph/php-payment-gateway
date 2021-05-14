<?php

use Gateway\Data\ModelConfig;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/consumer.php";
require_once __DIR__ . "/../config/connection.php";

/**
 * @method Consumer|false findById(string $id) 
 */
class ConsumerRepository extends \Gateway\Data\Repository
{
    public function __construct()
    {
        $modelConfig = new ModelConfig('consumers', Consumer::class);
        foreach (get_class_vars($modelConfig->getClass()) as $key => $value) {
            if ($key == 'id') {
                $modelConfig->addField($key, true);
            } else {
                $modelConfig->addField($key, false);
            }
        }
        parent::__construct($modelConfig, PDOCONN::$instance);
    }
}
