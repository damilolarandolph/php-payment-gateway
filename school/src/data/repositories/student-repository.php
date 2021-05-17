<?php

use Gateway\Data\ModelConfig;
use Gateway\Data\Repository;

require_once __DIR__ . "/../../../../common/data/repository.php";
require_once __DIR__ . "/../models/student.php";
require_once __DIR__ . "/../config/connection.php";

class StudentRepository extends Repository
{
    public function __construct()
    {
        $modelConfig = new ModelConfig('students', Student::class);
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
