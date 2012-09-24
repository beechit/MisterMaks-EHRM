<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-09-12 09:35
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * MainMenu renderer
 *
 * @FLOW3\Scope("prototype")
 */
class MainMenu extends AbstractTypoScriptObject {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @FLOW3\Inject
	 */
	protected $settingsHelper;

	/**
	 * @return string
	 */
	public function evaluate() {
		$this->initializeView();

		$this->view->assignMultiple(array(
			'mainMenuItems' => $this->settingsHelper->getMenuItems('main'),
			'actionMenuItems' => $this->settingsHelper->getMenuItems('action'),
			'accountMenuItems' => $this->settingsHelper->getMenuItems('account')
		));

		return str_replace(array(chr(9), chr(10)), '', $this->view->render());
	}

}

?>