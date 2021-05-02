<?php

namespace Gateway\Data;


class ModelConfig
{
    /** @var string[] $fields */
    private $fields;

    private $pk;

    private $table;

    private $class;

    public function __construct($table, $class)
    {
        $this->fields = [];
        $this->pk = "";
        $this->table = $table;
        $this->class = $class;
    }

    public function addField($fieldName, $isPK)
    {
        if ($isPK) {
            $this->pk = $fieldName;
        }
    }

    public function getId()
    {
        return $this->pk;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getClass()
    {
        return $this->class;
    }
}
