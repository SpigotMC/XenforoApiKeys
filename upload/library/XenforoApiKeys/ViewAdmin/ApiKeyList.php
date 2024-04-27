<?php

class XenforoApiKeys_ViewAdmin_ApiKeyList extends XenForo_ViewAdmin_Base
{
  public function renderJson()
  {
    if (!empty($this->_params['filterView'])) {
      $this->_templateName = 'api_key_list_item';
    }

    return null;
  }
}