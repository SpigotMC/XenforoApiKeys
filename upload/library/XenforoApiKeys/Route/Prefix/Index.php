<?php

class XenforoApiKeys_Route_Prefix_Index implements XenForo_Route_Interface {
    public function match($routePath, Zend_Controller_Request_Http $request, Xenforo_Router $router) {
        return $router->getRouteMatch('XenforoApiKeys_ControllerPublic_Index', $routePath);
    }
}