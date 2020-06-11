<?php
require_once 'DbConn.php';

class ArduinoState extends DbConn
{
    public $status_time;
    public $curr_state;
    public $device_id;

    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single() {
        $query = 'SELECT * FROM ' . $this->table . ' 
                ORDER BY `status_time` DESC
                LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function create() {
        $query =  'INSERT INTO ' . $this->table . '
        SET status_time = :status_time, curr_state = :curr_state, device_id = :device_id;
            ';

        $stmt = $this->conn->prepare($query);

        // clean
        $this->status_time = htmlspecialchars(strip_tags($this->status_time));
        $this->curr_state = htmlspecialchars(strip_tags($this->curr_state));
        $this->device_id = htmlspecialchars(strip_tags($this->device_id));

        // bind
        $stmt->execute([
            ':status_time' => $this->status_time,
            ':curr_state' => $this->curr_state,
            ':device_id' => $this->device_id
        ]);
        return true;
//        if () {
//            return true;
//        }
//     printf("Error: %s\n", $stmt->errorCode());
//        return false;
    }
}
