<?php

class XenforoApiKeys_Route_Prefix_ApiKeys implements XenForo_Route_Interface {
    public function match($routePath, Zend_Controller_Request_Http $request, Xenforo_Router $router) {
        // always go to XenforoApiKeys_ControllerPublic_ApiKeyController#actionView
        return $router->getRouteMatch('XenforoApiKeys_ControllerPublic_ApiKeyController', 'view', 'api-keys');
    }

    public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams) {
        return XenForo_Link::buildBasicLink($outputPrefix, $action, $extension);
    }
}