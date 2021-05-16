<?php

use Gateway\Data\ModelConfig;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/card.php";
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../../../../common/utils/aes-encrypt.php";

/**
 * @method Card|false findById(string $id) 
 */
class CardRepository extends \Gateway\Data\Repository
{
    public function __construct()
    {
        $modelConfig = new ModelConfig('cards', Card::class);
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
