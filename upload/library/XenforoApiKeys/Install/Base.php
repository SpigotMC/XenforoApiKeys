<?php

class XenforoApiKeys_Install_Base {
    public static function install($previous) {
        $db = Xenforo_Application::getDb();

        $db->query("
            CREATE TABLE IF NOT EXISTS `xf_api_keys` (
                `user_id` INT UNSIGNED PRIMARY KEY,
                `key` CHAR(64) UNIQUE NOT NULL
            )
        ");
    }

    public static function uninstall() {
        Xenforo_Application::getDb()->query("DROP TABLE IF EXISTS `xf_api_keys`");
    }
}
