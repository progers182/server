<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With, X-Forwarded-For');

include_once '../config/ACDatabase.php';
include_once '../models/ArduinoState.php';
include_once '../models/Commands.php';
include_once '../models/Devices.php';
include_once '../models/States.php';

// Connect to DB
$db = new ACDatabase();
$conn = $db->connect();

$id = isset($_GET['table']) ? $_GET['table'] : -1;
$table = $db->getTableName($id);

if ($id > -1 && !empty($table)) {
    $insert_request = selectClass($conn, $table);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));
    $data->ip_address = getenv('REMOTE_ADDR');// "172.0.0.1";  // for testing;
    if ($insert_request->create($data)) {
        echo json_encode([
            'message' => $data,
        ]);
    } else {
        echo json_encode([
            'message' => 'Error'
        ]);
    }

} else {
    echo json_encode([
        'Error message' => 'Please specify database table to POST to'
    ]);
}

function selectClass($conn, $table) {

    switch ($table) {
        case 'arduino_state':
            return new ArduinoState($conn);
            break;
        case 'commands':
            return new Commands($conn);
            break;
        case 'device_ids':
            return new Devices($conn);
            break;
//        case 'state_ids':
//            return new StateIds($conn);
//            break;
        default:
            return null;
    }
}