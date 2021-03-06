<?php

namespace Gateway\Data;

use Gateway\Util\UUID;

require_once __DIR__  . "/model-config.php";
require_once __DIR__ . "/../utils/gen-uuid.php";


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
        $this->conn = $conn;
    }

    /**
     * @param string|false $id
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

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->getClass());
        $res = $stmt->fetch();
        return $res;
    }
    public function find($options, ...$params)
    {
        $q = "SELECT * FROM {$this->modelConfig->getTable()} $options";
        $stmt = $this->conn->prepare($q);
        $stmt->execute($params);
        $res = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->getClass());
        return $res;
    }

    public function findAll()
    {
        $q = "SELECT * FROM {$this->modelConfig->getTable()}";
        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->getClass());
        return $res;
    }

    public function findOne($options, ...$params)
    {
        $q = "SELECT * FROM {$this->modelConfig->getTable()} $options LIMIT 1";
        $stmt = $this->conn->prepare($q);
        $stmt->execute($params);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelConfig->getClass());
        $res = $stmt->fetch();
        return $res;
    }

    public function save(&$model)
    {
        $fields = implode(",", $this->modelConfig->getFields());
        $qMarks = str_repeat('?,', count($this->modelConfig->getFields()));
        $qMarks = substr($qMarks, 0, strlen($qMarks) - 1);
        $q = "INSERT INTO {$this->modelConfig->getTable()} ($fields) VALUES ($qMarks)";
        $stmt = $this->conn->prepare($q);
        $fieldArray = [];
        if (empty($model->{$this->modelConfig->getId()})) {
            $model->{$this->modelConfig->getId()} =  UUID::v4();
        }
        foreach ($this->modelConfig->getFields() as $field) {
            $fieldArray[] = $model->{$field};
        }
        $stmt->execute($fieldArray);
    }

    public function update($model)
    {
        $updateString = "";
        foreach ($this->modelConfig->getFields() as $field) {
            $updateString .= "{$field} = ?, ";
        }
        $updateString = substr($updateString, 0, strlen($updateString) - 2);
        $q = "UPDATE {$this->modelConfig->getTable()} SET {$updateString} WHERE {$this->modelConfig->getId()} = ?";
        $stmt = $this->conn->prepare($q);
        $fieldArray = [];
        foreach ($this->modelConfig->getFields() as $field) {
            $fieldArray[] = $model->{$field};
        }
        $fieldArray[] = $model->{$this->modelConfig->getId()};
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
