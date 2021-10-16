<?php
include_once '../../abstract_db/Database.php';
use Server\Database;
class SeamDatabase extends Database
{
    const TABLES = [
        1 => 'users',
        2 => 'devices',
    ];

    public function __construct()
    {
        $this->setIniPath(__DIR__ . DIRECTORY_SEPARATOR . 'db_config.ini');
    }

    public function getTableName($id) {
        if (isset(self::TABLES[$id])) {
            return self::TABLES[$id];
        }
        return '';
    }
}