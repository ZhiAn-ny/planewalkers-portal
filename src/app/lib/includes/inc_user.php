<?php
require "inc_db_connection.php";

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