<?php
class Devices {
    private $conn;
    private $table = 'device_ids';

    public $internal_id;
    public $ip_address;
    public $username;
    public $is_blocked;

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
