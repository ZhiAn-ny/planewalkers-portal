<?php
require "inc_user.php";
require "inc_utils.php";
sec_session_start();

$response = array();
$response['status'] = 500;
$response['ok'] = false;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        $rawPostData = file_get_contents("php://input");
        $decodedData = json_decode($rawPostData, true);
        if ($decodedData == null && json_last_error() != JSON_ERROR_NONE) {
            $response['status'] = 400;
            throw new Exception('Invalid payload.');
        }
        $userManager = new UserManager();
        $u = json_decode($decodedData['user']);
        $user = new User($u->id, $u->username, $u->since, $u->name, $u->email, $u->xp, $u->bio);
        $response['message'] = $userManager->updateUser($user);
    } 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $username = $_GET['username'] ?? '';
        $readAchievements = $_GET['a'] ?? '1';
        $searchSimilarUsername = $_GET['ssu'] ?? '0';
        $userManager = new UserManager();
        if ($searchSimilarUsername == '1') {
            $maxCount = 15;
            $arr = $userManager->getUsernameLike($username, $maxCount);
            $res = '[';
            for ($i = 0; $i < count($arr) ; $i++) {
                $res = $res.'"'.$arr[$i].'"';
                if ($i != count($arr)-1) {
                    $res = $res.', ';
                }
            }
            $res = $res.']';
            $response['message'] = $res;
        } else {
            if ($username == null || $username == '') {
                $username = $_SESSION['username'];
            }
            $res = $userManager->getUser($username);
            if ($readAchievements == '1') {
                $achManager = new AchievementsManager();
                $achs = $achManager->getUserAchievements($res);
                $res->addAchievements(...$achs);
            }
            $response['message'] = $res->toString();
        }
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
