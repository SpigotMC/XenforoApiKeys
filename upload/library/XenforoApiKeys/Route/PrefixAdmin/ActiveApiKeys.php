<?php

class XenforoApiKeys_Route_PrefixAdmin_ActiveApiKeys extends XenForo_Route_PrefixAdmin_Nodes
{
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        return $router->getRouteMatch('XenforoApiKeys_ControllerAdmin_ActiveApiKeys', $routePath, 'api_keys_active_keys');
    }
}