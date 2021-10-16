<?php
include_once '../config/SeamDatabase.php';
include_once '../models/devices.php';
include_once '../../abstract_db/Database.php';
include_once '../../abstract_db/DatabaseConnection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With, X-Forwarded-For');

$db = new SeamDatabase();
$conn = $db->connect();
$devices = new Devices($conn);
$query = new QueryBuilder();

// device db queries testing

//$query->select('t.test')->from('table', 't')->where('');
//echo json_encode($devices->addDevice(['user_id' => 1,'name' => 'parkers test', 'ip' => '192.168.0.5']));
//echo json_encode($devices->getSingleDevice(3));
//$devices = $devices->getAllDevices(1);
//    echo json_encode($devices);
//$devices->removeSingleDevice(17);
//$devices->removeAllDevices(1);

// user db queries testing
