<?php
namespace Server\DbConn;
include_once 'Devices.php';

class Commands extends DbConn {
    private $command;
    private $device_id;

    public function read() {
        $query = 'SELECT * FROM `commands`;';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single() {
        $query = 'SELECT * FROM `commands`
                    ORDER BY `commands`.update_time DESC 
                    LIMIT 1;';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    public function create($data, $web = null) {
        if (key_exists('device_id', $data)) {
            $this->device_id = $data->device_id;
        }
        else if (! $web) {
            $this->device_id = $this->getDeviceId($data->ip_address);
        }
        else {
            $this->device_id = -1;
        }

        $query = 'INSERT INTO `commands`
        SET update_time = CURRENT_TIMESTAMP(), command = :command, device_id = :device_id;
            ';

        $stmt = $this->conn->prepare($query);

        // clean
        $this->command = htmlspecialchars(strip_tags($data->command));

        // bind
        $stmt->execute([
            ':command' => $this->command,
            ':device_id' => $this->device_id
        ]);
        return true;
    }

    public function getDeviceId($address) {
        $device = new Devices($this->conn);

        return $device->get_device($address, ["internal_id"])["internal_id"];
    }

    public function queueState() {
        $query = 'SELECT * FROM `arduino_state`
                    ORDER BY `arduino_state`.status_time DESC 
                    LIMIT 1;';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $ard_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $cmd_data = $this->read_single()->fetch(PDO::FETCH_ASSOC);
        $is_updated = $cmd_data['command'] == $ard_data['curr_state'];

        date_default_timezone_set('America/Denver');
        return [
            "is_updated" => $is_updated,
            "curr_state" => $ard_data['curr_state'],
            "command" => $cmd_data['command'],
            "device_id" => $cmd_data['device_id'],
            "curr_hour" => date('H')
        ];
    }
}
