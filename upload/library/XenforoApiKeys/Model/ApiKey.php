<?php

class XenforoApiKeys_Model_ApiKey extends XenForo_Model {
    public function getApiKeyForUser($userId) {
        return $this->_getDb()->fetchRow('SELECT * FROM `xf_api_keys` WHERE `user_id` = ?', $userId);
    }

    // maybe shouldn't be in the model. but whatever, lol.
    public function generateKey() {
        return hash("sha256", bin2hex(random_bytes(32)));
    }
}