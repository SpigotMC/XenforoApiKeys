<?php

class XenforoApiKeys_Install_Controller {
    public static function install($previous) {
        $db = Xenforo_Application::getDb();

        if (!$previous) {
            // We are installing fresh - need to make tables and assign default keys to all users
            $db->query("
                CREATE TABLE IF NOT EXISTS `xf_api_keys` (
                    `user_id` int unsigned auto_increment primary key,
                    `key` char(64) unique not null
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ");

            self::_assignApiKeysToAllUsers($db);
        } else {
            // We are upgrading
            // Nothing to do at the moment
        }
    }

    public static function uninstall() {
        Xenforo_Application::getDb()->query("DROP TABLE IF EXISTS `xf_api_keys`");
    }

    protected static function _assignApiKeysToAllUsers($db) {
        $users = $db->fetchAll("SELECT `user_id`, `email`, `register_date` FROM `xf_user` ORDER BY `user_id` ASC");
        
        XenForo_Db::beginTransaction($db);
        
        foreach ($users as $user) {
            $db->query("INSERT INTO `xf_api_keys` (`user_id`, `key`) VALUES (?, ?);", [$user['user_id'], self::_generateKey($user)]);
        }

        XenForo_Db::commit($db);
    }

    protected static function _generateKey($user) {
        // turns the string "{id}-{email}-{register date}-{unix time}" into a hash
        // this will be the api key
        return hash(
            "sha256", 
            $user['user_id'] . "-" . $user['email'] . "-" . $user['register_date'] . "-" . microtime()
        );
    }
}
