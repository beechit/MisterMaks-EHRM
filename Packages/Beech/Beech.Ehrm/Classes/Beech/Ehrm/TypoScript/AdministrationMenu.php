<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-10-12 14:26
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * MainMenu renderer
 *
 * @Flow\Scope("prototype")
 */
class AdministrationMenu extends AbstractTypoScriptObject {

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
			'settings' => $this->settings
		));

		return str_replace(array(chr(9), chr(10)), '', $this->view->render());
	}

}

?>