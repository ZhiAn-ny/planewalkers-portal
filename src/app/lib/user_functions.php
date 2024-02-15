<?php
require "includes/inc_user.php";
sec_session_start();

$response = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $rawPostData = file_get_contents("php://input");
        $decodedData = json_decode($rawPostData, true);
        if ($decodedData == null && json_last_error() != JSON_ERROR_NONE) {
            $response['status'] = 400;
            throw new Exception('Invalid payload.');
        }
        $action = $decodedData['action'] ?? '';

        if ($action == 'get') {
            $username = $decodedData['username'] ?? $_SESSION['username'];
            $res = getUser($username);
            $response['message'] = json_encode($res);
        }
        else if ($action == 'update') {

        }

        $response['status'] = 200;
        $response['ok'] = true;
    } catch (\Exception $e) {
        $response['ok'] = false;
        $response['message'] = $e.getMessage();        
    }
    http_response_code($response['status']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} 
