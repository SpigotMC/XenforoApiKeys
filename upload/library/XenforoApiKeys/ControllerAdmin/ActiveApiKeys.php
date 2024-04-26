<?php

class XenforoApiKeys_ControllerAdmin_ActiveApiKeys extends XenForo_ControllerAdmin_Abstract
{
  protected function _preDispatch($action)
  {
    $this->assertAdminPermission('manage_api_keys');
  }

  protected function _filterActiveApiKeySearchCriteria(array $criteria)
	{
		return $this->_getCriteriaHelper()->filterActiveApiKeySearchCriteria($criteria);
	}

  protected function _prepareActiveApiKeySearchCriteria(array $criteria)
  {
    return $this->_getCriteriaHelper()->prepareActiveApiKeySearchCriteria($criteria);
  }

  public function actionIndex()
  {
    $criteria = $this->_input->filterSingle('criteria', XenForo_Input::JSON_ARRAY);
    $criteria = $this->_filterActiveApiKeySearchCriteria($criteria);

    $filter = $this->_input->filterSingle('_filter', XenForo_Input::ARRAY_SIMPLE);
    if ($filter && isset($filter['value'])) {
      $criteria['username2'] = array($filter['value'], empty($filter['prefix']) ? 'lr' : 'r');
      $filterView = true;
    } else {
      $filterView = false;
    }

    $order = $this->_input->filterSingle('order', XenForo_Input::STRING);
    $direction = $this->_input->filterSingle('direction', XenForo_Input::STRING);

    $page = $this->_input->filterSingle('page', XenForo_Input::UINT);
    $usersPerPage = 20;

    $showingAll = $this->_input->filterSingle('all', XenForo_Input::UINT);
    if ($showingAll) {
      $page = 1;
      $usersPerPage = 5000;
    }

    $fetchOptions = array(
      'perPage' => $usersPerPage,
      'page' => $page,

      'order' => $order,
      'direction' => $direction
    );

    $apiKeyModel = $this->_getApiKeyModel();

    $criteriaPrepared = $this->_prepareActiveApiKeySearchCriteria($criteria);

    $totalUsers = $apiKeyModel->countUsersWithApiKeys($criteriaPrepared);
    if (!$totalUsers) {
      return $this->responseError(new XenForo_Phrase('no_users_matched_specified_criteria'));
    }

    $users = $apiKeyModel->getUsersWithApiKeys($criteriaPrepared, $fetchOptions);

    $viewParams = array(
      'users' => $users,
      'totalUsers' => $totalUsers,
      'showingAll' => $showingAll,
      'showAll' => (!$showingAll && $totalUsers <= 5000),

      'linkParams' => array('criteria' => $criteria, 'order' => $order, 'direction' => $direction),
      'page' => $page,
      'usersPerPage' => $usersPerPage,

      'filterView' => $filterView,
      'filterMore' => ($filterView && $totalUsers > $usersPerPage)
    );

    return $this->responseView('XenforoApiKeys_ViewAdmin_ActiveApiKeys', 'active_api_keys', $viewParams);
  }

  protected function _getApiKeyModel()
  {
    return $this->getModelFromCache('XenforoApiKeys_Model_ApiKey');
  }

  /**
   * @return XenforoApiKeys_ControllerHelper_ActiveApiKeysCriteria
   */
  protected function _getCriteriaHelper()
  {
    return $this->getHelper('XenforoApiKeys_ControllerHelper_ActiveApiKeysCriteria');
  }
}