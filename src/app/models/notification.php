<?php

if (!defined('NTF_MODEL')) {
    define('NTF_MODEL', true);

    enum NotificationType: int {
        case FRIEND_REQUEST = 1;
        case FRIEND_ACCEPTED = 2;
        case MESSAGE = 3;
    }

    class Notification {
        private int $id = 0;
        private NotificationType $type;
        private string $title;
        private string $content;
        private int $targetUser = 0;
        private string $sender = '';
        private bool $readFlag = false;
        private $createdAt = null;
        
        function __construct($id, $type, $target, $title, $content, $sender, $read, $creationDate) {            
            $this->title = $title;
            $this->content = $content;
            $this->id = $id;
            $this->type = $type;
            $this->targetUser = $target;
            $this->sender = $sender;
            $this->readFlag = $read;
            $this->createdAt = $creationDate;
        }

        function getID() { return $this->id; }
        function getNotificationType() { return $this->type; }
        function getTitle() { return $this->title; }
        function getContent() { return $this->content; }
        function getTargetUser() { return $this->targetUser; }
        function getSender() { return $this->sender; }
        function getCreationDate() { return $this->createdAt; }
        function isRead() { return $this->readFlag; }

        function toString(): string {
            return '{ "id": '.$this->id.', '.
                '"type": '.$this->type.', '.
                '"targetUser": '.$this->targetUser.', '.
                '"sender": "'.$this->sender.'", '.
                '"title": "'.$this->title.'", '.
                '"content": "'.$this->content.'", '.
                '"readFlag": '.$this->readFlag.', '.
                '"createdAt": "'.$this->createdAt->format('Y-m-d').'" '.
                '}';
        }

    }
}
