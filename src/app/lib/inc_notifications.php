<?php

if (!defined('INC_NOTIF')) {
    define('INC_NOTIF', true);

    require("inc_db_connection.php");
    require "../models/notificationFactory.php";

    class NotificationManager {

        public function read(int $userID, $onlyPending = true): array {
            $notifications = [];
            $mysqli = connect();
            $qry = "SELECT id, title, content, notification_type, sender,
                read_flag, created_at
                FROM notifications
                WHERE target_user = ?";
            if ($onlyPending) {
                $qry .= " AND read_flag = 0";
            }
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->bind_result($ID, $title, $content, $type, $sender, $readFlag, $date);
            while ($row = $stmt->fetch()) {
                $dateTime = DateTime::createFromFormat("Y-m-d H:i:s", $date);
                $notif = new Notification(
                    $ID, NotificationType::tryFrom($type), $userID,
                    $title, $content, $sender, $readFlag, $dateTime
                );
                $notifications[] = $notif;
            }
            return $notifications;
        }

        public function sendFriendRequest(User $currentUser, int $target) {
            $notification = NotificationFactory::newFriendRequest($currentUser, $target);
            $this->sendNotification($notification);
        }

        private function sendNotification(Notification $notif) {
            $mysqli = connect();
            $qry = "INSERT INTO notifications
            (notification_type, target_user, title, content, sender)
            VALUES
            (?, ?, ?, ?, ?)";
            $notificationType = $notif->getNotificationType()->value;
            $targetUser = $notif->getTargetUser();
            $title = $notif->getTitle();
            $content = $notif->getContent();
            $sender = $notif->getSender();
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param('iissi', $notificationType, $targetUser, $title, $content, $sender);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
        }
    }
}