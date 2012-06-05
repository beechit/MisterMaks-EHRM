<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Ehrm".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Ehrm\Helper\SettingsHelper as SettingsHelper;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class AbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Initialize Mvc action
	 *
	 * @return void
	 */
	public function initializeAction() {
		SettingsHelper::convertMenuActionsToUrls($this->settings, $this->uriBuilder);
	}

	/**
	 * @param \TYPO3\FLOW3\Mvc\View\ViewInterface $view
	 * @return void
	 */
	public function initializeView(\TYPO3\FLOW3\Mvc\View\ViewInterface $view) {
		$view->assign('mainmenuItems', SettingsHelper::getMainMenuItems($this->settings));
	}

}

?>