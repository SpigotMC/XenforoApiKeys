<?php

class XenforoApiKeys_Model_ApiKey extends XenForo_Model
{
  public function getApiKeyForUser($userId)
  {
    return $this->_getDb()->fetchRow('SELECT * FROM `xf_api_keys` WHERE `user_id` = ?', $userId);
  }

  public function countUsersWithApiKeys(array $conditions)
  {
    $whereClause = $this->prepareUserConditions($conditions);
    return $this->_getDb()->fetchOne(
      "SELECT COUNT(xfa.user_id)
       FROM `xf_api_keys` AS xfa 
        INNER JOIN `xf_user` AS xfu ON xfu.user_id = xfa.user_id 
       WHERE " . $whereClause
    );
  }

  public function getUsersWithApiKeys(array $conditions, array $fetchOptions = array())
  {
    $whereClause = $this->prepareUserConditions($conditions);
    $orderClause = $this->prepareUserOrderOptions($fetchOptions, 'username');
    $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

    return $this->fetchAllKeyed($this->limitQueryResults(
      "SELECT xfu.user_id, xfu.username, xfa.key
       FROM `xf_api_keys` AS xfa 
        INNER JOIN `xf_user` AS xfu ON xfu.user_id = xfa.user_id 
       WHERE " . $whereClause . " " . $orderClause,
      $limitOptions['limit'],
      $limitOptions['offset']
    ), 'user_id');
  }

  public function prepareUserConditions(array $conditions)
  {
    $db = $this->_getDb();
    $sqlConditions = array();

    if (!empty($conditions['username'])) {
      if (is_array($conditions['username'])) {
        $sqlConditions[] = 'xfu.`username` LIKE ' . XenForo_Db::quoteLike($conditions['username'][0], $conditions['username'][1], $db);
      } else {
        $sqlConditions[] = 'xfu.`username` LIKE ' . XenForo_Db::quoteLike($conditions['username'], 'lr', $db);
      }
    }

    // this is mainly for dynamically filtering a search that already matches user names
    if (!empty($conditions['username2'])) {
      if (is_array($conditions['username2'])) {
        $sqlConditions[] = 'xfu.`username` LIKE ' . XenForo_Db::quoteLike($conditions['username2'][0], $conditions['username2'][1], $db);
      } else {
        $sqlConditions[] = 'xfu.`username` LIKE ' . XenForo_Db::quoteLike($conditions['username2'], 'lr', $db);
      }
    }

    return $this->getConditionsForClause($sqlConditions);
  }

  public function prepareUserOrderOptions(array &$fetchOptions, $defaultOrderSql = '')
  {
    $choices = array('username' => 'username');
    return $this->getOrderByClause($choices, $fetchOptions, $defaultOrderSql);
  }

  // maybe shouldn't be in the model. but whatever, lol.
  public function generateKey()
  {
    return hash("sha256", bin2hex(random_bytes(32)));
  }
}