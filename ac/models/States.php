<?php
class StateIds {
    private $conn;
    private $table = 'state_ids';

    public $state;
    public $state_id;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT * FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
