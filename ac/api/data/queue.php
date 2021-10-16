<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../config/ACDatabase.php';
include_once '../models/Commands.php';
include_once '../models/Devices.php';

// Connect to DB
$db = new ACDatabase();
$conn = $db->connect();


$cmd = new Commands($conn);

echo json_encode($cmd->queueState());


