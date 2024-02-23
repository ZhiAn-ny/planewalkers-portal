<?php
if (!defined('ACH_ENUM')) {
    define('ACH_ENUM', true);

    enum AchievementsID: int {
        case ALIAS_ADEPT = 1;
        case ORIGINS_ORACLE = 2;
    }
}