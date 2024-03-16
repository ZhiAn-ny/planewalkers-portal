<?php

if (!defined('INC_NOTIF')) {
    define('INC_NOTIF', true);

    require("inc_db_connection.php");
    require "../models/notificationFactory.php";

    class NotificationManager {

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