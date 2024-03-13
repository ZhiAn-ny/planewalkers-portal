<?php
require("inc_friends.php");
sec_session_start();

$params;
$response = array();
$response['status'] = 500;
$response['ok'] = false;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $params = $_GET;
    } else {
        $rawPostData = file_get_contents("php://input");
        $decodedData = json_decode($rawPostData, true);
        if ($decodedData == null && json_last_error() != JSON_ERROR_NONE) {
            $response['status'] = 400;
            throw new Exception('Invalid payload.');
        }
        $params = $decodedData;
    }
    $fm = new FriendshipManager();
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $username = $_SESSION['username'];
            $target = $_GET['t'] ?? '';
            $response['message'] = $fm->checkFriendshipStatus($username, $target);
            break;
        case 'POST':
            break;
        case 'PATCH':
    }
    $response['status'] = 200;
    $response['ok'] = true;
} catch (Exception $e) {
    $response['vdump'] = print_r($e);
    $response['message'] = $e.getMessage();        
}
http_response_code($response['status']);
header('Content-Type: application/json');
echo json_encode($response);
exit();
