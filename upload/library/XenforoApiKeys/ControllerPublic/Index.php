<?php

class XenforoApiKeys_ControllerPublic_Index extends XenForo_ControllerPublic_Abstract {
    public function actionIndex() {
        $user = XenForo_Visitor::getInstance();
        if (!$this->_doesUserExist($user['user_id'])) {
          return $this->responseNoPermission();
        }

        $apiKey = $this->_getApiKeyModel()->getApiKeyForUser($user['user_id']);

        $perm_set = $user['permissions'];

        $viewParams = [
            'can_have_api_key' => $this->_checkPermission($perm_set, 'obtain_api_key'),
            'can_rotate_api_key' => $this->_checkPermission($perm_set, 'rotate_api_key'),
            'can_delete_api_key' => $this->_checkPermission($perm_set, 'delete_api_key'),
            'has_api_key' => $apiKey['key'] !== NULL,
            'api_key' => $apiKey['key']
        ];
        
        return $this->getHelper('Account')->getWrapper(
            'account', 'api-keys',
            $this->responseView(
                'XenforoApiKeys_ViewPublic_Index',
                'account_api_key',
                $viewParams
            )
        );
    }

    public function actionCreateKey() {
        $this->_assertPostOnly();
        $this->_checkCsrf('createKey');

        $user = XenForo_Visitor::getInstance();
        if (!$this->_doesUserExist($user['user_id'])) {
          return $this->responseNoPermission();
        }

        if (!$this->_checkPermission($user["permissions"], "obtain_api_key")) {
          return $this->responseNoPermission();
        }

        $writer = XenForo_DataWriter::create("XenforoApiKeys_DataWriter_ApiKey");
        $writer->set('user_id', $user['user_id']);
        $writer->set('key', $this->_getApiKeyModel()->generateKey($user));
        $writer->save();

        if ($this->_noRedirect()) {
            return $this->actionIndex();
        }

        return $this->responseRedirect(
            XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('api-keys'),
            "Your API key has been created."
        ); 
    }

    public function actionRotateKey() {
        $this->_assertPostOnly();
        $this->_checkCsrf('rotateKey');

        $user = XenForo_Visitor::getInstance();
        if (!$this->_doesUserExist($user['user_id'])) {
          return $this->responseNoPermission();
        }

        if (!$this->_checkPermission($user["permissions"], "rotate_api_key")) {
          return $this->responseNoPermission();
        }

        $writer = XenForo_DataWriter::create("XenforoApiKeys_DataWriter_ApiKey");
        $writer->setExistingData($user['user_id'], true);
        $writer->set('key', $this->_getApiKeyModel()->generateKey($user));
        $writer->save();

        if ($this->_noRedirect()) {
            return $this->actionIndex();
        }

        return $this->responseRedirect(
            XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('api-keys'),
            "Your API key has been changed. The old one will no longer work!"
        );
    }

    public function actionDeleteKey() {
        $this->_assertPostOnly();
        $this->_checkCsrf('deleteKey');

        $user = XenForo_Visitor::getInstance();
        if (!$this->_doesUserExist($user['user_id'])) {
          return $this->responseNoPermission();
        }

        if (!$this->_checkPermission($user["permissions"], "delete_api_key")) {
          return $this->responseNoPermission();
        }

        $writer = XenForo_DataWriter::create("XenforoApiKeys_DataWriter_ApiKey");
        $writer->setExistingData($user['user_id'], true);
        $writer->delete();

        if ($this->_noRedirect()) {
            return $this->actionIndex();
        }

        return $this->responseRedirect(
            XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('api-keys'),
            "Your API key has been deleted."
        );
    }

    protected function _getApiKeyModel() {
        return $this->getModelFromCache('XenforoApiKeys_Model_ApiKey');
    }

    protected function _doesUserExist($user_id) {
      return $this->getModelFromCache('XenForo_Model_User')->getUserById($user_id) !== false;
    }

    protected function _checkPermission($perm_set, $perm) {
      return XenForo_Permission::hasPermission($perm_set, "xenforo_api_keys", $perm);
    }
}