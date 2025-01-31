<?php

if (!defined('USR_MODEL')) {
    define('USR_MODEL', true);

    class User {
        private int $id = 0;
        private string $username = '';
        private $since;
        private string $name;
        private string $bio;
        private int $xp;
        private string $email;
        private $achievements = [];
        
        function __construct(int $id, string $username, $since=null,
            string $name='', string $email='', int $xp=0, string $bio='') {
            $this->id = $id;
            $this->username = $username;
            $this->since = $since;
            $this->name = $name;
            $this->email = $email;
            $this->xp = $xp;
            $this->bio = $bio;
        }

        function getUsername() { return $this->username; }
        function getName() { return $this->name; }
        function getBio() { return $this->bio; }
        function getXP() { return $this->xp; }
        function getID() { return $this->id; }
        function getEmail() { return $this->email; }

        function getLevel(): int {
            if ($this->xp < 300) return 1;
            else if ($this->xp < 900) return 2;
            else if ($this->xp < 2700) return 3;
            else if ($this->xp < 6500) return 4;
            else if ($this->xp < 14000) return 5;
            else if ($this->xp < 23000) return 6;
            else if ($this->xp < 34000) return 7;
            else if ($this->xp < 48000) return 8;
            else if ($this->xp < 64000) return 9;
            else if ($this->xp < 85000) return 10;
            else if ($this->xp < 100000) return 11;
            else if ($this->xp < 120000) return 12;
            else if ($this->xp < 140000) return 13;
            else if ($this->xp < 165000) return 14;
            else if ($this->xp < 195000) return 15;
            else if ($this->xp < 225000) return 16;
            else if ($this->xp < 265000) return 17;
            else if ($this->xp < 305000) return 18;
            else if ($this->xp < 355000) return 19;
            else return 20;
        }

        /** Adds the specified achievements into the list of achievements of
         *  the current user. */
        function addAchievements(Achievement ...$achievements) {
            foreach ($achievements as $achievement) {
                array_push($this->achievements, $achievement);
            }
        }

        function toString(): string {
            $userStr =  '{ "id": '.$this->id.', '.
                '"username": "'.$this->username.'", '.
                '"since": "'.explode(' ', $this->since)[0].'", '.
                '"name": "'.$this->name.'", '.
                '"email": "'.$this->email.'", '.
                '"bio": "'.$this->bio.'", '.
                '"xp": '.$this->xp.', '.
                '"lv": '.$this->getLevel().', '.
                '"achievements": [';
            for ($i = 0; $i < count($this->achievements) ; $i++) {
                $ach = $this->achievements[$i];
                $str = $ach->toString();
                $userStr = $userStr.$str;
                if ($i != count($this->achievements)-1) {
                    $userStr = $userStr .', ';
                }
            }
            $userStr = $userStr.'] }';
            return $userStr;
        }
        
    }
}
