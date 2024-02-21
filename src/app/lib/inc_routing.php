<?php
require "inc_db_connection.php";
sec_session_start();

if (!defined('INC_ROUTING')) {
    define('INC_ROUTING', true);

    enum Pages: int {
        case NotFound = -1;
        case Landing = 0;
        case Home = 1;
        case Auth = 2;
        case Profile = 3;
    }

    function redirect(Pages $pageId) {
        $redirect = "Location: " .getRedirectUrl($pageId);
        header($redirect);
        // switch ($pageId) {
        //     case Pages::Landing:
        //         break;
        //     case Pages::Home:
        //         header("Location: http://localhost/pwp/src/app/dashboard");
        //         break;
        //     case Pages::Auth:
        //         header("Location: http://localhost/pwp/src/app/auth/login.php");
        //         break;
        //     case Pages::Profile:
        //         header("Location: http://localhost/pwp/src/app/my");
        //         break;
        //     default:
        //         header("Location: http://localhost/pwp/page_not_found.php");
        //     }
        exit();
    }

    function getRedirectUrl(Pages $pageId) {
        switch ($pageId) {
            case Pages::Landing:
                return "http://localhost/pwp/src/app/landing";
            case Pages::Home:
                return "http://localhost/pwp/src/app/dashboard";
            case Pages::Auth:
                return "http://localhost/pwp/src/app/auth/login.php";
            case Pages::Profile:
                return "http://localhost/pwp/src/app/my";
            default:
                return "http://localhost/pwp/page_not_found.php";
        }
    }
}
