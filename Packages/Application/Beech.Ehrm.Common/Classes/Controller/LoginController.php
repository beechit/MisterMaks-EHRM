<?php
namespace Beech\Ehrm\Common\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Ehrm.Common".          *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Ehrm\Common\Helper\SettingsHelper as SettingsHelper;

/**
 * Login controller for the Beech.Ehrm.Common package
 *
 * @FLOW3\Scope("singleton")
 */
class LoginController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @param array $settings
	 */
	public function initializeAction() {
		SettingsHelper::convertMenuActionsToUrls($this->settings, $this->uriBuilder);
	}

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function loginAction() {
		\TYPO3\FLOW3\var_dump(SettingsHelper::getMainMenuItems($this->settings));

		$this->view->assign('items', SettingsHelper::getMainMenuItems($this->settings));
	}

}

?>