<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-09-12 09:35
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * MainMenu renderer
 *
 * @Flow\Scope("prototype")
 */
class MainMenu extends AbstractTypoScriptObject {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	public function evaluate() {
		$this->initializeView();

		$this->view->assignMultiple(array(
			'settings' => $this->settings,
			'mainMenuItems' => $this->settingsHelper->getMenuItems('main'),
			'actionMenuItems' => $this->settingsHelper->getMenuItems('action'),
			'accountMenuItems' => $this->settingsHelper->getMenuItems('account')
		));

		return str_replace(array(chr(9), chr(10)), '', $this->view->render());
	}

}

?>