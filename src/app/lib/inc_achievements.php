<?php
if (!defined('INC_ACH')) {
    define('INC_ACH', true);

    require "inc_db_connection.php";
    require "../models/user.php";
    require "../models/achievements.php";

    class AchievementsManager {

        public function recordAchievement(User $user, AchievementsID $achievement) {
            $mysqli = connect();
            $qry = "INSERT INTO user_achievements
                    (user_id, achievement_id, date_earned)
                    VALUES (?, ?, NOW())";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('ii', $user->getID(), $achievement);
                $stmt->execute();
                return $stmt->affected_rows == 1;
            }
            return false;
        }

        public function getUserAchievements(User $user) {
            $mysqli = connect();
            $qry = "INSERT INTO user_achievements
                    (user_id, achievement_id, date_earned)
                    VALUES (?, ?, NOW())";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('ii', $user->getID(), $achievement);
                $stmt->execute();
                return $stmt->affected_rows == 1;
            }
            return false;
        }

    }
}