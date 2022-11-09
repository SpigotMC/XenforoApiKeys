<?php

class XenforoApiKeys_Hooks_AccountWrapperSidebarSettings {
    public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template) {
        if ($hookName === "account_wrapper_sidebar_settings") {
            $contents .= $template->create("account_wrapper_sidebar_api_key", $template->getParams())->render();
        }
    }
}