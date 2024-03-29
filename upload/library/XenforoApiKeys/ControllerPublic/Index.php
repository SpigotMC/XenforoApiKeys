<?php

class XenforoApiKeys_ControllerPublic_Index extends XenForo_ControllerPublic_Abstract {
    public function actionIndex() {
        $apiKeyModel = $this->_getApiKeyModel();

        $apiKey = $apiKeyModel->getApiKeyForUser(XenForo_Visitor::getUserId());

        $viewParams = [
            'can_have_api_key' => XenForo_Permission::hasPermission(XenForo_Visitor::getInstance()["permissions"], "xenforo_api_keys", "obtain_api_key"),
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

        $user = XenForo_Visitor::getInstance();

        $writer = XenForo_DataWriter::create("XenforoApiKeys_DataWriter_ApiKey");
        $writer->set('user_id', $user['user_id']);
        $writer->set('key', $this->_getApiKeyModel()->generateKey());
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

        $user = XenForo_Visitor::getInstance();

        $writer = XenForo_DataWriter::create("XenforoApiKeys_DataWriter_ApiKey");
        $writer->setExistingData($user['user_id'], true);
        $writer->set('key', $this->_getApiKeyModel()->generateKey());
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

        $user = XenForo_Visitor::getInstance();

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
}