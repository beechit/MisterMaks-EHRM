<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 03-05-12 22:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Ehrm".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Ehrm\Helper\SettingsHelper as SettingsHelper;

/**
 * Login controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class LoginController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Initialize Mvc action
	 *
	 * @return void
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
		$this->view->assign('items', SettingsHelper::getMainMenuItems($this->settings));
	}

}

?>