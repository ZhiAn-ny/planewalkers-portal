<?php
require "inc_db_connection.php";
sec_session_start();

if (!defined('INC_ROUTING')) {
    define('INC_ROUTING', true);

    enum Pages: int {
        case NOT_FOUND = -1;
        case LANDING = 0;
        case HOME = 1;
        case AUTH = 2;
        case PROFILE = 3;
    }

    function redirect(Pages $pageId) {
        $redirect = "Location: " .getRedirectUrl($pageId);
        header($redirect);
        exit();
    }

    function getRedirectUrl(Pages $pageId) {
        switch ($pageId) {
            case Pages::LANDING:
                return "http://localhost/pwp/src/app/landing";
            case Pages::HOME:
                return "http://localhost/pwp/src/app/dashboard";
            case Pages::AUTH:
                return "http://localhost/pwp/src/app/auth/login.php";
            case Pages::PROFILE:
                return "http://localhost/pwp/src/app/my";
            default:
                return "http://localhost/pwp/page_not_found.php";
        }
    }
}
