<?php

class XenforoApiKeys_Model_ApiKey extends XenForo_Model
{
    public function getApiKeyForUser($userId)
    {
        return $this->_getDb()->fetchRow('SELECT * FROM `xf_api_keys` WHERE `user_id` = ?', $userId);
    }

    public function getUsersWithApiKeys($page = 1, $limit = 50)
    {
        $offset = max(0, $page - 1) * $limit;

        return $this->_getDb()->fetchAll("
            SELECT xfu.user_id, xfu.username, xfa.key 
            FROM `xf_api_keys` xfa 
                INNER JOIN `xf_user` xfu ON xfu.user_id = xfa.user_id
            LIMIT ?, ?",
            [$offset, $limit]
        );
    }

    // maybe shouldn't be in the model. but whatever, lol.
    public function generateKey($user)
    {
        // turns the string "{id}-{email}-{register date}-{unix time}" into a hash
        // this will be the api key
        return hash(
            "sha256",
            $user['user_id'] . "-" . $user['email'] . "-" . $user['register_date'] . "-" . microtime()
        );
    }
}