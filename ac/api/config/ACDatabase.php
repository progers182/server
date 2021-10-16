<?php
namespace Server\Database;

class ACDatabase extends Database
{
//    private $ini_path = '/ac/api/config/db_config.ini';
    const TABLES = [
        1 => 'arduino_state',
        2 => 'commands',
        3 => 'device_ids',
        4 => 'state_ids'
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