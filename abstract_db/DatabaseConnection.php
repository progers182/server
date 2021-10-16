<?php

use Server\Exception\DatabaseException;

include_once (__DIR__ . '/DatabaseException.php');
include_once (__DIR__ . '/QueryBuilder.php');

abstract class DatabaseConnection
{
    private $stmt;
    private $query = '';

    // error code consts
    private const NO_QUERY_SET = 'Error: query was not properly set before execution';
    private const NO_QUERY_SET_CODE = 1;

    // query build consts
    protected const SELECT = 1;
    protected const UPDATE = 2;
    protected const DELETE = 3;
    protected const INSERT = 4;



    protected $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    protected function setQuery($query) {
        $this->query = $query;
    }

    private function getQuery() {
        return $this->query;
    }

    private function prepareQuery() {
        $this->stmt = $this->conn->prepare($this->getQuery());
    }

    private function execQuery($params) {
        $this->stmt->execute($params);
    }

    private function results() {
        $results = [];
        while($result = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
            if (key_exists('ip', $result)) {
                $result['ip'] = long2ip($result['ip']);
            }
                $results[] = $result;
        }
        return $results;
    }

    protected function run($params = []) {
        if ($this->getQuery() === '') {
            throw new DatabaseException(self::NO_QUERY_SET, self::NO_QUERY_SET_CODE);
        }

        $this->prepareQuery();
        $this->execQuery($params);
        return $this->results();
    }
}