<?php

if (!defined('INC_USR')) {
    define('INC_USR', true);

    require "inc_db_connection.php";
    require "inc_achievements.php";

    class UserManager {
        //// CHECK
    
        public function isUsernameTaken(string $username): bool {
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
    
        public function emailExists(string $email): bool {
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
    
        //// READ
    
        public function getUser(string $username, $mysqli = null): User {
            if ($mysqli == null) {
                $mysqli = connect();
            }
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

        public function getUserFromID(int $user_id, $mysqli = null): User {
            if ($mysqli == null) {
                $mysqli = connect();
            }
            $qry = "SELECT id, username, since, name, email, xp, bio FROM members 
                    WHERE id = ? LIMIT 1";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($user_id, $username, $since, $name, $email, $xp, $bio);
                $stmt->fetch();
                return new User($user_id, $username, $since, $name, $email, $xp, $bio);
            }
            return null;
        }
    
        //// WRITE
    
        public function updateUser(User $user): string {
            $mysqli = connect();
            $other = $this->getUser($user->getUsername(), $mysqli);
            if ($other != null && $other->getID() != $user->getID()) {
                return "Username already taken.";
            }
            //$result = $this->checkUpdateAchievements($user, $mysqli);

            if ($this->update($user, $mysqli)) {
                return '{ "success" : 1 }';
            }
            return "Error occurred during query execution.";
        }

        // private function checkUpdateAchievements(User $user, $mysqli = null) {
        //     if ($mysqli == null) {
        //         $mysqli = connect();
        //     }
        //     $achManager = new AchievementsManager()

        //     $result = '{ "achievements": [';
        //     $orig = $this->getUserFromID($user->getID(), $mysqli);

        //     if ($orig->getName() == "" && $user->getName() != "") {
                
        //     }
        //     $result = $result.'] }';
        // }

        private function update(User $user, $mysqli = null) {
            if ($mysqli == null) {
                $mysqli = connect();
            }
            $qry = "UPDATE members SET
                      username = ?,
                      name = ?,
                      bio = ?,
                      xp = ?
                    WHERE id = ?";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('sssii', $user->getUsername(), $user->getName(),
                                  $user->getBio(), $user->getXP(), $user->getID());
                $stmt->execute();
                return $stmt->affected_rows == 1;
            }
            return false;
        }
    }
}
