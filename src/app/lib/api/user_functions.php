<?php
require "../includes/inc_user.php";
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
            $username = $decodedData['username'];
            if ($username == null || $username == '') {
                $username = $_SESSION['username'];
            }
            $res = getUser($username);
            $response['message'] = $res->toString();
            $response['usr'] = $username;
        }
        else if ($action == 'update') {
            $response['message'] = 'update';
        } else {
            $response['message'] = 'else';

        }
        $response['status'] = 200;
        $response['ok'] = true;
    } catch (\Exception $e) {
        $response['status'] = 500;
        $response['ok'] = false;
        $response['message'] = $e.getMessage();        
    }
    http_response_code($response['status']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} 
