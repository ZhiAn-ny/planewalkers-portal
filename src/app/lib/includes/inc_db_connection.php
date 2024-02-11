<?php
if (!defined('INC_CONNECTION')) {
    define('INC_CONNECTION', true);

    define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
    define("USER", "sec_user"); // E' l'utente con cui ti collegherai al DB.
    define("PASSWORD", "GsCQK71cbfmfzKcPXG3"); // Password di accesso al DB.
    define("DATABASE", "tecweb_pwpel"); // Nome del database.

    function sec_session_start() {
        $session_name = 'sec_session_id'; // Imposta un nome di sessione
        $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
        $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
        ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
        $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
        session_start(); // Avvia la sessione php.
        session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
    }

    function connect() {
        if (session_status() != PHP_SESSION_ACTIVE) {
            sec_session_start();
        }
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error != 0) {
            $err = $mysqli->connect_error;
            $err_date = date("F j, Y, h:i a");
            $msg = "{$err} | {$err_date} \r\n";
            file_put_contents("db-log.txt", $msg, FILE_APPEND);
            return false;
        }
        return $mysqli;
    }
}
