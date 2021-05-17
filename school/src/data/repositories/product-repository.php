<?php

use Gateway\Data\ModelConfig;
use Gateway\Data\Repository;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/product.php";
require_once __DIR__ . "/../config/connection.php";

class ProductRepository extends Repository
{
    public function __construct()
    {
        $modelConfig = new ModelConfig('products', Product::class);
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
