<?php
require "inc_db_connection.php";
require "../models/user.php";

if (!defined('INC_USR')) {
    define('INC_USR', true);

    function isUsernameTaken(string $username): bool {
        $mysqli = connect();
        $stmt = $mysqli->prepare("SELECT username FROM members WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result != NULL) {
            return true;
        }
        return false;
    }

    function emailExists(string $email): bool {
        $mysqli = connect();
        $stmt = $mysqli->prepare("SELECT email FROM members WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result != NULL) {
            return true;
        }
        return false;
    }

    function getUser(string $username): User {
        $mysqli = connect();
        $qry = "SELECT id, username, since, name, email, xp, bio FROM members 
                WHERE username = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($qry)) { 
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $username, $since, $name, $email, $xp, $bio);
            $stmt->fetch();
            return new User($user_id, $username, $since, $name, $email, $xp, $bio);
        }
        return null;
    }

}
