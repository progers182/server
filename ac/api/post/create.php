<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/ArduinoState.php';

// Connect to DB
$db = new Database();
$conn = $db->connect();


$ard_state = new ArduinoState($conn, 'arduino_state');

// Get posted data
$data = json_decode(file_get_contents("php://input"));

$ard_state->status_time = $data->status_time;
$ard_state->curr_state = $data->curr_state;
$ard_state->device_id = $data->device_id;

if ($ard_state->create()) {
    echo json_encode([
        'message' => 'Data received'
    ]);
} else {
    echo json_encode([
        'message' => 'Error'
    ]);
}
