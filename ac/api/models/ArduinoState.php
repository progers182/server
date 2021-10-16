<?php
namespace Server\DbConn;

class ArduinoState extends DbConn {
    private $curr_state;
    private $device_id;

    public function read() {
        $query = 'SELECT * FROM `arduino_state`';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single() {
        $query = 'SELECT s.state, a.curr_state, d.username, a.status_time  FROM `state_ids` AS s
                    INNER  JOIN `arduino_state` AS a ON s.state_id = a.curr_state
                    INNER JOIN `device_ids` AS d ON a.device_id = d.internal_id
                    ORDER BY a.status_time DESC
                    LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function create($data) {
        $query = 'INSERT INTO `arduino_state`
        SET status_time = CURRENT_TIMESTAMP(), curr_state = :curr_state, device_id = :device_id;';

        $stmt = $this->conn->prepare($query);

        // clean
        $this->curr_state = htmlspecialchars(strip_tags($data->curr_state));
        $this->device_id = htmlspecialchars(strip_tags($data->device_id));

        // bind
        $stmt->execute([
            ':curr_state' => $this->curr_state,
            ':device_id' => $this->device_id
        ]);
        return true;
    }
}
