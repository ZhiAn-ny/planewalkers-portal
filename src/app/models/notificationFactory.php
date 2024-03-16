<?php

if (!defined('NTFFAC_MODEL')) {
    define('NTFFAC_MODEL', true);

    require("notification.php");
    require("user.php");

    class NotificationFactory {
        //$id, $type, $target, $title, $content, $sender, $read, $creationDate

        public static function newFriendRequest(User $currentUser, int $targetUser): Notification {
            return new Notification(
                0, NotificationType::FRIEND_REQUEST, $targetUser,
                "New freind request", $currentUser->getUsername()." asked to be your friend.",
                $currentUser->getID(), false, null
            );
        }

        public static function friendRequestAccepted(User $currentUser, int $targetUser): Notification {
            return new Notification(
                0, NotificationType::FRIEND_ACCEPTED, $targetUser,
                "Friendship accepted", $currentUser->getUsername()." has accepted your friend request!",
                $currentUser->getID(), false, null
            );
        }

    }
}
