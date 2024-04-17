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
            $currentUser = new User($_SESSION['user_id'], $_SESSION['username']);
            $target = (int)$params['t'] ?? 0;
            $friendRequests = $nm->read(
                $target, true, NotificationType::FRIEND_REQUEST, (int)$_SESSION['user_id']
            );
            if (count($friendRequests) == 0) {
                $friendRequest = NotificationFactory::newFriendRequest($currentUser, $target);
                $nm->sendNotification($friendRequest);
            }
            $fm->sendFriendRequest($currentUser->getID(), $target);
            break;
        case 'DELETE':
            $user = $_SESSION['user_id'];
            $target = (int)$params['t'] ?? 0;
            $fm->deleteFriendship($user, $target);
            $friendRequests = $nm->read(
                $target, true, NotificationType::FRIEND_REQUEST, (int)$_SESSION['user_id']
            );
            if (count($friendRequests) == 1) {
                $nid = $friendRequests[0]->getID();
                $nm->delete($nid);
            }
            break;
        case 'PATCH':
            $user = new User($_SESSION['user_id'], $_SESSION['username']);
            $reqTarget = (int)$params['t'] ?? 0;
            $reqSender = (int)$params['sender'] ?? 0;
            $notifId = (int)$params['nid'] ?? 0;
            $accepted = $params['accepted'] ?? '';
            if ($accepted === 'true') {
                $fm->acceptFriendRequest($reqSender, $reqTarget);
                $nm->markAsRead($notifId);
                $nm->sendNotification(NotificationFactory::friendRequestAccepted($user, $reqSender));
            } else if ($accepted === 'false') {
                $fm->deleteFriendship($reqSender, $reqTarget);
                $nm->markAsRead($notifId);
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
