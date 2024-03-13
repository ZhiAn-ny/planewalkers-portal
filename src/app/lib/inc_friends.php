<?php

if (!defined('INC_FRN')) {
    define('INC_FRN', true);

    require("inc_db_connection.php");

    enum FriendshipStatus: string {
        case ACCEPTED = 'accepted';
        case PENDING = 'pending';
        case REJECTED = 'rejected';
        case NOT_FOUND = '';
    }

    class FriendshipManager {

        public function checkFriendshipStatus($user, $other): FriendshipStatus {
            $mysqli = connect();
            $qry = "SELECT friendship_status FROM friendship
                WHERE (user1_id = ? OR user2_id = ?) AND (user1_id = ? OR user2_id = ?)";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param("ssss", $user, $user, $other, $other);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($status);
            $stmt->fetch();
            $stmt->close();
            $mysqli->close();
            return FriendshipStatus::tryFrom($status);
        }

    }
}