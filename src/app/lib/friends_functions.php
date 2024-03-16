<?php
require("inc_friends.php");
require("inc_notifications.php");
sec_session_start();

$params = [];
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
            $user = $_SESSION['user_id'];
            $target = (int)$params['t'] ?? 0;
            $response['message'] = $fm->checkFriendshipStatus($user, $target);
            break;
        case 'POST':
            $user = new User($_SESSION['user_id'], $_SESSION['username']);
            $target = (int)$params['t'] ?? 0;
            $nf = new NotificationManager();
            $nf->sendFriendRequest($user, $target);
            $fm->sendFriendRequest($user->getID(), $target);
            break;
        case 'DELETE':
            $user = $_SESSION['user_id'];
            $target = (int)$params['t'] ?? 0;
            $fm->deleteFriendship($user, $target);
            break;
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
