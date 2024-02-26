<?php
if (!defined('INC_ACH')) {
    define('INC_ACH', true);

    require "inc_db_connection.php";
    require "../models/user.php";
    require "../models/achievements.php";

    class AchievementsManager {

        public function recordAchievement(User $user, AchievementsID $achievement) {
            $affected = 0;
            $ach;
            $uid = $user->getID();
            $aid = $achievement->value;
            $mysqli = connect();
            $qry = "INSERT INTO user_achievements
                    (user_id, achievement_id, date_earned)
                    VALUES (?, ?, NOW())";
            $qry2 = "UPDATE members SET
                    xp = xp + (SELECT xp FROM achievements WHERE id = ?)
                    WHERE id = ?";
            $qry3 = "SELECT name, description, xp, fa_class 
                    FROM achievements WHERE id = ?";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('ii', $uid, $aid);
                $stmt->execute();
                $affected = $stmt->affected_rows;
                $stmt->close();
            }
            if ($affected == 1 && $stmt = $mysqli->prepare($qry2)) { 
                $stmt->bind_param('ii', $aid, $uid);
                $stmt->execute();
                $affected = $stmt->affected_rows;
                $stmt->close();
            }
            if ($affected == 1 && $stmt = $mysqli->prepare($qry3)) { 
                $stmt->bind_param('i', $aid);
                $stmt->execute();
                $stmt->bind_result($name, $desc, $xp, $faClass);
                $stmt->fetch();
                $ach = new Achievement($aid, $name, $desc, $xp, $faClass);
                $stmt->close();
            }
            $mysqli->close();
            return $ach;
        }

        public function getUserAchievements(User $user) {
            $achievements = array();
            $uid = $user->getID();
            $mysqli = connect();
            $qry = "SELECT user_achievements.user_id, user_achievements.achievement_id,
                    achievements.name, achievements.description, achievements.fa_class,
                    achievements.xp, user_achievements.date_earned
                    FROM user_achievements
                    LEFT JOIN achievements ON user_achievements.achievement_id = achievements.ID
                    WHERE user_achievements.user_id = ?";
            if ($stmt = $mysqli->prepare($qry)) { 
                $stmt->bind_param('i', $uid);
                $stmt->execute();
                $stmt->bind_result($user_id, $ach_id, $name, $desc, $faclass, $xp, $date);
                while ($row = $stmt->fetch()) {
                    $dateTime = DateTime::createFromFormat("Y-m-d", $date);
                    $ach = new Achievement($ach_id, $name, $desc, $xp, $faclass, $dateTime);
                    $achievements[] = $ach;
                }
                $stmt->close();
            }
            $mysqli->close();
            return $achievements;
        }

    }
}