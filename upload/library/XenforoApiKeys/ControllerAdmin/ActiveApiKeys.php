<?php

class XenforoApiKeys_ControllerAdmin_ActiveApiKeys extends XenForo_ControllerAdmin_Abstract
{
    protected function _preDispatch($action)
    {
        $this->assertAdminPermission('manage_api_keys');
    }

    public function actionIndex()
    {
        $viewParams = [
            'data' => $this->_getApiKeyModel()->getUsersWithApiKeys()
        ];

        return $this->responseView('XenforoApiKeys_ViewAdmin_ActiveApiKeys', 'active_api_keys', $viewParams);
    }

    protected function _getApiKeyModel()
    {
        return $this->getModelFromCache('XenforoApiKeys_Model_ApiKey');
    }
}