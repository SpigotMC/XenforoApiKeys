<?php

class XenforoApiKeys_Hooks_AccountWrapperSidebarSettings
{
    public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
        if (XenForo_Permission::hasPermission(XenForo_Visitor::getInstance()["permissions"], "xenforo_api_keys", "obtain_api_key")) {
            if ($hookName === "account_wrapper_sidebar_settings") {
                $contents .= $template->create("account_wrapper_sidebar_api_key", $template->getParams())->render();
            }
        }
    }
}