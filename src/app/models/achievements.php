<?php
if (!defined('ACH_MODEL')) {
    define('ACH_MODEL', true);

    enum AchievementsID: int {
        case ALIAS_ADEPT = 1;
        case ORIGINS_ORACLE = 2;
    }

    class Achievement {
        private int $id = 0;
        private string $name = '';
        private string $desc = '';
        private int $xp = 0;
        private string $faClass = '';
        private ?DateTime $dateEarned = null;

        function __construct(
            int $id, string $name, string $desc, int $xp, 
            string $faClass, DateTime $dateEarned = null
        ) {
            $this->id = $id;
            $this->name = $name;
            $this->desc = $desc;
            $this->xp = $xp;
            $this->faClass = $faClass;
            $this->dateEarned = $dateEarned;
        }

        function getID() { return $this->id; }
        function getName() { return $this->name; }
        function getDescription() { return $this->desc; }
        function getXP() { return $this->xp; }
        function getFaClass() { return $this->faClass; }
        function getDateEarned() { return $this->dateEarned; }

        function toString(): string {
            $r = '{ "id":'.$this->id.', '.
                '"name":"'.$this->name.'", '.
                '"desc":"'.$this->desc.'", '.
                '"xp":'.$this->xp.', '.
                '"faClass":"'.$this->faClass.'"';
            if ($this->dateEarned != null) {
                $r = $r.', "dateEarned":"'.$this->dateEarned->format('Y-m-d').'" ';
            }
            $r = $r.'}';
            return $r;
        }
    }
}