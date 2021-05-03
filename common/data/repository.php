<?php

namespace Gateway\Data;

require_once __DIR__  . "/model-config.php";


abstract class Repository
{

    /**
     * @var ModelConfig $modelConfig
     */
    protected $modelConfig;

    /**
     * @var \PDO $conn
     */
    protected $conn;

    public function __construct($modelConfig, $conn)
    {
        $this->modelConfig = $modelConfig;
    }

    /**
     * @param string|int $id
     * 
     */
    public function findById($id)
    {
        $q = "SELECT * FROM {$this->modelConfig->getTable()} WHERE {$this->modelConfig->getId()} = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($id));
        if ($stmt->rowCount() == 0) {
            return false;
        }

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->class);
        $res = $stmt->fetch();
        return $res;
    }
    public function find($options, ...$params)
    {
        $q = "SELECT * FROM {$this->modelConfig->getTable()} $options";
        $stmt = $this->conn->prepare($q);
        $stmt->execute($params);
        $res = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->class);
        return $res;
    }
    public function save($model)
    {
        $fields = implode(",", $this->modelConfig->getFields());
        $qMarks = str_repeat('?,', count($this->modelConfig->getFields()));
        $qMarks = substr($qMarks, 0, strlen($qMarks) - 1);
        $q = "INSERT INTO {$this->modelConfig->getTable()} ($fields) ($qMarks)";
        $stmt = $this->conn->prepare($q);
        $fieldArray = [];
        foreach ($this->modelConfig->getFields() as $field) {
            $fieldArray[] = $model->{$field};
        }
        $stmt->execute($fieldArray);
    }
    public function deleteById($id)
    {
        $q = "DELETE FROM {$this->modelConfig->getTable()} WHERE {$this->modelConfig->getId()} = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($id));
    }
    public function deleteWhere($whereStatement, ...$params)
    {
        $q = "DELETE FROM {$this->modelConfig->getTable()} WHERE {$whereStatement}";
        $stmt = $this->conn->prepare($q);
        $stmt->execute($params);
    }
}