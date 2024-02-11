<?php

if (!defined('INC_ROUTING')) {
    define('INC_ROUTING', true);


    enum Pages: int {
        case Landing = 0;
        case Home = 1;
        case Auth = 2;
        case Profile = 3;
    }

    function redirect(Pages $pageId) {
        switch ($pageId) {
            case Pages::Landing:
                header("Location: http://localhost/pwp/src/app/landing");
                break;
            case Pages::Home:
                header("Location: http://localhost/pwp/src/app/dashboard");
                break;
            case Pages::Auth:
                header("Location: http://localhost/pwp/src/app/auth/login.php");
                break;
            case Pages::Profile:
                header("Location: http://localhost/pwp/src/app/my");
                break;
            default:
                header("Location: http://localhost/pwp/page_not_found.php");
            }
        exit();
    }
}
                
// $response = array();
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $rawPostData = file_get_contents("php://input");
//     $decodedData = json_decode($rawPostData, true);
//     if ($decodedData == null && json_last_error() != JSON_ERROR_NONE) {
//         $response['ok'] = false;
//         $response['status'] = 400;
//         $response['message'] = 'Invalid payload.';
//         http_response_code(400);
//         header('Content-Type: application/json');
//         echo json_encode($response);
//     } else {
//         $pageId = $decodedData['id'] ?? '';    
//         $response['ok'] = true;
//         $response['status'] = 200;
//         $response['message'] = 'Success. Page redirect: ' . $pageId;
//         http_response_code(200);
//         header('Content-Type: application/json');
//         echo json_encode($response);
//         $page = Pages::tryFrom($pageId);
//         redirect($page);
//     }
//     exit();
// }
// else {
//     $response['ok'] = false;
//     $response['status'] = 500;
//     $response['message'] = 'Only POST request possible.';
//     http_response_code(500);
//     header('Content-Type: application/json');
//     echo json_encode($response);
//     exit();
// }
