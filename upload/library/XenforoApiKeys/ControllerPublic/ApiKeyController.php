<?php

class XenforoApiKeys_ControllerPublic_ApiKeyController extends XenForo_ControllerPublic_Abstract {
    public function actionView() {
        $result = Xenforo_Application::getDb()->fetchRow("SELECT `key` FROM `xf_api_keys` WHERE `user_id` = ? LIMIT 1", [XenForo_Visitor::getUserId()]);
        $viewParams = ['api_key' => $result['key']];

        return $this->getHelper('Account')->getWrapper(
            'api-keys',
            'view',
            $this->responseView('XenforoApiKeys_ViewPublic_ApiKey', 'xak_api_key', $viewParams)
        );
    }
}