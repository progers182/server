<?php
abstract class DbConn {

    protected $table = '';
    protected $conn;

    public function __construct(PDO $db, $table) {
        $this->conn = $db;
        $this->table = $table;
    }

    abstract function read();
    abstract function read_single();

    abstract function create();
}