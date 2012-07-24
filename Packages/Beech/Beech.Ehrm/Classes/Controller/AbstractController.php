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

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class AbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @FLOW3\Inject
	 */
	protected $settingsHelper;

	/**
	 * @param \TYPO3\FLOW3\Mvc\View\ViewInterface $view
	 * @return void
	 */
	public function initializeView(\TYPO3\FLOW3\Mvc\View\ViewInterface $view) {
		$view->assign('mainMenuItems', $this->settingsHelper->getMenuItems('main'));
		$view->assign('actionMenuItems', $this->settingsHelper->getMenuItems('action'));
		$view->assign('accountMenuItems', $this->settingsHelper->getMenuItems('account'));
	}

}

?>