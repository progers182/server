<?php
include_once '../../abstract_db/DatabaseConnection.php';
include_once '../../abstract_db/QueryBuilder.php';

class Devices extends DatabaseConnection
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

    /**
     * get all devices from user
     * @param $user_id
     * @return array
     * @throws \Server\Exception\DatabaseException
     */
    public function getAllDevices($user_id)
    {
        $this->query->select('d.id', 'd.name', 'd.ip')->from('devices', 'd')->where('d.user_id', 'd.status', 'd.is_deleted');
        $this->setQuery($this->query);
        return $this->run(['user_id' => $user_id, 'status' => 'A', 'is_deleted' => 0]);
    }

    /**
     * get single device -- intended to establish connection -- may not be necessary
     * @param $device_id
     * @return array
     * @throws \Server\Exception\DatabaseException
     */
    public function getSingleDevice($device_id)
    {
        $this->query->select('d.name', 'd.ip')->from('devices', 'd')->where('d.id', 'd.status', 'd.is_deleted');
        $this->setQuery($this->query);
        return $this->run(['id' => $device_id, 'status' => 'A', 'is_deleted' => 0]);
    }

    /**
     * removes a single device by setting the is_deleted field to true
     *
     * @param $deviceId
     * @throws \Server\Exception\DatabaseException
     */
    public function removeSingleDevice($deviceId)
    {
        $this->query->delete()->from('devices')->where('id');
        $this->setQuery($this->query);
        echo $this->query;
        $this->run(['id' => $deviceId]);

    }

    /**
     * removes all devices for user by setting the is_deleted field to true
     *
     * @param $userId
     * @throws \Server\Exception\DatabaseException
     */
    public function removeAllDevices($userId)
    {
        $this->query->delete()->from('devices')->where('devices.user_id');
        $this->setQuery($this->query);
        $this->run(['user_id' => $userId]);
    }

    /**
     * add new device
     *
     * @param array $device
     * @return array
     * @throws \Server\Exception\DatabaseException
     */
    public function addDevice(array $device)
    {
        $this->query->insert('devices.user_id', 'devices.name', 'devices.ip')->into('devices');
        $this->setQuery($this->query);

        // convert ip for storing in db
        $device['ip'] = ip2long($device['ip']);
        return $this->run($device);
    }

}