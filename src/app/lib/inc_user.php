<?php

if (!defined('INC_USR')) {
    define('INC_USR', true);

    require "inc_utils.php";
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
    
        public function getUser(string $username, $mysqli = null): ?User {
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
                if ($user_id)
                    return new User($user_id, $username, $since, $name, $email, $xp, $bio);
            }
            return null;
        }

        public function getUserFromID(int $user_id, $mysqli = null): ?User {
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
                if ($username) {
                    return new User($user_id, $username, $since, $name, $email, $xp, $bio);
                }
            }
            return null;
        }
    
        //// WRITE
    
        public function updateUser($user): string {
            $other = $this->getUser($user->getUsername());
            if ($other != null && $other->getID() != $user->getID()) {
                return "Username already taken.";
            }
            $result = $this->checkUpdateAchievements($user);
            if ($this->update($user)) {
                $_SESSION['user_id'] = $user->getID(); 
                $_SESSION['username'] = $user->getUsername();
                return $result;
            }
            return "Error occurred during query execution.";
        }

        private function checkUpdateAchievements(User $user) {
            $achManager = new AchievementsManager();
            $achs = $achManager->getUserAchievements($user);
            $added = '{ "achievements" : [';
            $hasOrig = array_column($achs, null, 'id')[AchievementsID::ORIGINS_ORACLE->value] ?? false;
            $hasAlias = array_column($achs, null, 'id')[AchievementsID::ALIAS_ADEPT->value] ?? false;
            if (!$hasAlias && $user->getName() != "") {
                $new = $achManager->recordAchievement($user, AchievementsID::ALIAS_ADEPT);
                $added = $added . $new;
            }
            if (!$hasOrig && $user->getBio() != "") {
                $new = $achManager->recordAchievement($user, AchievementsID::ORIGINS_ORACLE);
                if (!str_ends_with($added, '[')) {
                    $added = $added . ', ';
                }
                $added = $added . $new;
            }
            $added = $added.'] }';
            return $added;
        }

        /** Updates the user's username, name and bio */
        private function update(User $user, $mysqli = null) {
            if ($mysqli == null) {
                $mysqli = connect();
            }
            $username = $user->getUsername();
            $name = $user->getName();
            $bio = $user->getBio();
            $id = $user->getID();
            $qry = "UPDATE members SET
                      username = ?, name = ?, bio = ?
                    WHERE id = ?";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('sssi', $username, $name, $bio, $id);
                $stmt->execute();
                return $stmt->affected_rows == 1;
            }
            return false;
        }
    }
}
