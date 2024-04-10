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

        public function checkFriendshipStatus(int $user1, int $user2): array {
            $mysqli = connect();
            $qry = "SELECT friendship_status, user1_id, user2_id
                FROM friendship
                WHERE (user1_id = ? OR user2_id = ?) AND (user1_id = ? OR user2_id = ?)";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param("iiii", $user1, $user1, $user2, $user2);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($status, $sender, $target);
            $stmt->fetch();
            $stmt->close();
            $mysqli->close();
            return [
                'status' => $status,
                'sender' => $sender,
                'target' => $target
            ];
        }

        public function sendFriendRequest(int $sender, int $target) {
            $mysqli = connect();
            $qry = "INSERT INTO friendship (user1_id, user2_id, friendship_status)
                    VALUES (?, ?, 'pending')";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param('ii', $sender, $target);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
        }

        public function deleteFriendship(int $user1, int $user2) {
            $mysqli = connect(true);
            $qry = "DELETE FROM friendship
                    WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param('iiii', $user1, $user2, $user2, $user1);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
        }

        public function acceptFriendRequest(int $user1, int $user2) {
            $this->acceptFriend($user1, $user2);
            
        }

        private function acceptFriend(int $user1, int $user2) {
            $mysqli = connect(true);
            $qry = "UPDATE friendship
                    SET friendship_status = 'accepted'
                    WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param('iiii', $user1, $user2, $user2, $user1);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
        }
    }
}