<?php
require "inc_routing.php";

$response = array();
$response['status'] = 500;
$response['message'] = $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $rawPostData = file_get_contents("php://input");
        $decodedData = json_decode($rawPostData, true);
        if ($decodedData == null && json_last_error() != JSON_ERROR_NONE) {
            $response['status'] = 400;
            throw new Exception('Invalid payload.');
        }
        $pageId = $decodedData['page'] ?? '-1';
        $id = Pages::tryFrom($pageId);
        $url = getRedirectUrl($id);
        $response['idRequested'] = $pageId;
        $response['url'] = $url;
        $response['message'] = '{ "url" : "'.$url.'" }';   
        $response['status'] = 200;
        $response['ok'] = true;
        http_response_code($response['status']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(); 
    } catch (\Exception $e) {
        $response['status'] = 500;
        $response['ok'] = false;
        $response['message'] = $e.getMessage();        
    }
} 
http_response_code($response['status']);
header('Content-Type: application/json');
echo json_encode($response);
exit();
