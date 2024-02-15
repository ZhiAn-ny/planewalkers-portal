<?php
    require "includes/inc_user.php";
    require "includes/inc_routing.php";

    //// ARGS CHECKS

    function checkEmail(string $email, $mysqli, string $err): string {
        if ($err != "") {
            return $err;
        }
        $email = strtolower($email);
        if (emailExists($email, $mysqli)) {
            return "Email already exists.";
        }
        return "";
    }
    
    function checkUsername(string $username, $mysqli, string $err): string {
        if ($err != "") {
            return $err;
        }
        if (strlen($username) > 30) {
            return "Username too long.";
        }
        if (isUsernameTaken($username, $mysqli)) {
            return "Username already taken.";
        }
        return "";
    }
    
    function checkRegistrationArgs(string $email, string $username, string $password, string $confirmPassword) {
        $err = "";
        $args = array_map(function($value) { return trim($value); }, func_get_args());
        if ($password != $confirmPassword) {
            return "Password does not match.";
        }
        foreach ($args as $value) {
            if(empty($value)) {
                return "One or more fields are empty.";
            } else if (preg_match("/([<|>])/", $value)) {
                return "<> characters not allowed.";
            }
        }
        $mysqli = connect();
        $err = checkEmail($email, $mysqli, $err);
        $err = checkUsername($username, $mysqli, $err);
        $mysqli->close();
        session_destroy();
        if ($err != "") {
            return $err;
        }
    }

    // UTILITY & SECURITY
    
    function getUserLogin(string $user_id, $mysqli) {
        $qry = "SELECT password, salt FROM members 
                WHERE id = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($qry)) { 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result(); 
            return $stmt;
        }
        return null;
    }

    /**
     * @return true if is a brute force operation, false otherwise.
     */
    function isBruteTry(int $user_id, $mysqli) {
        $now = time();
        // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
        $valid_attempts = $now - (2 * 60 * 60); 
        $qry = "SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'";
        if ($stmt = $mysqli->prepare($qry)) { 
           $stmt->bind_param('i', $user_id); 
           $stmt->execute();
           $stmt->store_result();
           // Verifico l'esistenza di più di 5 tentativi di login falliti.
           if($stmt->num_rows > 5) {
              return true;
           }
        }
        return false;
    }

    function registerFailedLogin(int $user_id, $mysqli) {
        $now = time();
        $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");            
    }

    //// LOGIN & REGISTRATION

    function register(string $email, string $username, string $password, string $confirmPassword) {
        $err = checkRegistrationArgs($email, $username, $password, $confirmPassword);
        if ($err != "") {
            return $err;
        }
        $mysqli = connect();
        if (!$mysqli) {
            return "Connection failed.";
        }
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $hashedPassword = hash('sha512', $password.$random_salt);
        $qry = "INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)";
        if ($insert_stmt = $mysqli->prepare($qry)) {    
            $insert_stmt->bind_param('ssss', $username, $email, $hashedPassword, $random_salt); 
            $insert_stmt->execute();
            if ($insert_stmt->affected_rows == 1) {
                redirect(Pages::Home);
                exit();
            }
        }
        return "Error occurred, please try again.";
    }

    function login(string $userOrEmail, string $password) {
        $userOrEmail = trim($userOrEmail);
        $password = trim($password);
        if ($userOrEmail === ""|| $password === "") {
            return "Both fields are required.";
        }
        $userOrEmail = filter_var($userOrEmail, FILTER_SANITIZE_STRING);
        $password= filter_var($password, FILTER_SANITIZE_STRING);

        $mysqli = connect();
        if (!$mysqli) {
            return "Connection failed.";
        }
        if (!emailExists($userOrEmail) 
            && !isUsernameTaken($userOrEmail)) {
                return "Credentials not correct.";
        }
        $user = getUser($userOrEmail, $mysqli);

        if ($user != null) {
            $stmt = getUserLogin($user->getID(), $mysqli);
            $stmt->bind_result($db_password, $salt);
            $stmt->fetch();
            $password = hash('sha512', $password.$salt);

            if ($stmt->num_rows == 1 && !isBruteTry($user->getID(), $mysqli)) {
                if($db_password == $password) { 
                    $user_browser = $_SERVER['HTTP_USER_AGENT']; 
                    // Recupero il parametro 'user-agent' relativo all'utente corrente.
                    // ci proteggiamo da un attacco XSS
                    $user_id = preg_replace("/[^0-9]+/", "", $user->getID());
                    $_SESSION['user_id'] = $user_id; 
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
                    $home = Pages::Home;
                    redirect($home);
                    exit();
                } else {
                    registerFailedLogin($user->getID(), $mysqli);
                }
            } else {
                return "is brute";
            }
        } 
        return "Error occurred, please try again.";
    }
