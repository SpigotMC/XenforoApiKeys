<?php

class XenforoApiKeys_DataWriter_ApiKey extends XenForo_DataWriter {
    protected function _getFields() {
        return array(
            'xf_api_keys' => array(
                'user_id' => array('type' => self::TYPE_UINT, 'required' => true),
                'key' => array('type' => self::TYPE_STRING, 'required' => true)
            )
        );
    }

    protected function _getExistingData($data) {
        if (!$userId = $this->_getExistingPrimaryKey($data, 'user_id')) {
            return false;
        }

        return array('xf_api_keys' => $this->getModelFromCache('XenforoApiKeys_Model_ApiKey')->getApiKeyForUser($userId));
    }

    protected function _getUpdateCondition($tableName) {
        return 'user_id = ' . $this->_db->quote($this->getExisting('user_id'));
    }
}