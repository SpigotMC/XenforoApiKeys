<?php
class XenforoApiKeys_ControllerHelper_ActiveApiKeysCriteria extends XenForo_ControllerHelper_Abstract
{
  public function filterActiveApiKeySearchCriteria(array $criteria)
  {
    foreach ($criteria as $key => $value) {
      if ($value === '') {
        unset($criteria[$key]);
      }
    }

    return $criteria;
  }

  public function prepareActiveApiKeySearchCriteria(array $criteria)
  {
    foreach (array('username', 'username2') as $field) {
      if (isset($criteria[$field]) && is_string($criteria[$field])) {
        $criteria[$field] = trim($criteria[$field]);
      }
    }

    return $criteria;
  }
}