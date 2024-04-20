<?php
require "inc_notifications.php";
sec_session_start();

$notifManager = new NotificationManager();
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

    if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        $nid = $params['nid'] ?? 0;
        $notifManager->markAsRead($nid);
    } 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $user = $_SESSION['user_id'] ?? '';
        $onlyPending = $params['p'] ?? true;
        $notifications = $notifManager->read($user, $onlyPending);
        $resultStr = '[';
        foreach ($notifications as $notification) {
            $resultStr .= $notification->toString() . ',';
        }
        $resultStr = rtrim($resultStr, ',') . ']';
        $response['message'] = $resultStr;
    } 
    $response['status'] = 200;
    $response['ok'] = true;
} catch (\Exception $e) {
    $response['vdump'] = print_r($e);
    $response['message'] = $e.getMessage();        
}
http_response_code($response['status']);
header('Content-Type: application/json');
echo json_encode($response);
exit();
