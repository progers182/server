<?php

include_once '../../abstract_db/DatabaseConnection.php';
include_once '../../abstract_db/QueryBuilder.php';

class Users extends DatabaseConnection
{
    private $query;

    /**
     * Devices constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->query = new QueryBuilder();
    }

    function getUser($email) {
        $this->query->select('u.id', 'u.username')->from('users', 'u')->where('u.email');
        $this->setQuery($this->query);
        return $this->run(['email' => $email]);
    }

    function addUser ($user_info) {
        $this->query->insert('u.email')->into('users', 'u');
        $this->setQuery($this->query);
        $this->run($user_info);
    }
}