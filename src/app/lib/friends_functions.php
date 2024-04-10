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
    $nm = new NotificationManager();
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $user = $_SESSION['user_id'];
            $target = (int)$params['t'] ?? 0;
            $friendship = $fm->checkFriendshipStatus($user, $target);
            $response['message'] = '{'.
                '"sender":'.$friendship['sender'].','.
                '"status":"'.$friendship['status'].'",'.
                '"target":'.$friendship['target'].
                '}';
            break;
        case 'POST':
            $user = new User($_SESSION['user_id'], $_SESSION['username']);
            $target = (int)$params['t'] ?? 0;
            $friendRequest = NotificationFactory::newFriendRequest($currentUser, $target);
            $nm->sendNotification($friendRequest);
            $fm->sendFriendRequest($user->getID(), $target);
            break;
        case 'DELETE':
            $user = $_SESSION['user_id'];
            $target = (int)$params['t'] ?? 0;
            $fm->deleteFriendship($user, $target);
            break;
        case 'PATCH':
            $user = new User($_SESSION['user_id'], $_SESSION['username']);
            $target = (int)$params['target'] ?? 0;
            if ($target == $_SESSION['user_id'])
                $target = (int)$params['sender'] ?? 0;
            $accepted = $params['accepted'] ?? '';
            if ($accepted === 'true') {
                $acceptedNotif = NotificationFactory::friendRequestAccepted($user, $target);
                $fm->acceptFriendRequest($user, $target);
                $nf->sendNotification($acceptedNotif);
            } else if ($accepted === 'false') {
                $fm->deleteFriendship($user->getID(), $target);
            } else {
                throw new Exception('Invalid parameter');
            }
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
