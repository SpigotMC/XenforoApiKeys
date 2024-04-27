<?php

class XenforoApiKeys_Route_PrefixAdmin_ActiveApiKeys implements XenForo_Route_Interface
{
  public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
  {
    $action = $router->resolveActionWithIntegerParam($routePath, $request, 'user_id');
    return $router->getRouteMatch('XenforoApiKeys_ControllerAdmin_ActiveApiKeys', $action, 'api_keys_active_keys');
  }

  public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
  {
    return XenForo_Link::buildBasicLinkWithIntegerParam($outputPrefix, $action, $extension, $data, 'user_id', 'username');
  }
}