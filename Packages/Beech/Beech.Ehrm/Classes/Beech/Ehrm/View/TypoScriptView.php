<?php
namespace Beech\Ehrm\View;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 19:46
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("singleton")
 */
class TypoScriptView extends \TYPO3\TypoScript\View\TypoScriptView {

	/**
	 * @var string
	 */
	protected $typoScriptPathPattern = 'resource://Beech.Ehrm/Private/TypoScripts/';

	/**
	 * @var \TYPO3\Fluid\View\TemplateView
	 * @FLOW3\Inject
	 */
	protected $fallbackView;

	/**
	 * Initialize $this->typoScriptPath depending on the current controller and action
	 *
	 * @return void
	 */
	protected function initializeTypoScriptPathForCurrentRequest() {
		if ($this->typoScriptPath === NULL) {
			parent::initializeTypoScriptPathForCurrentRequest();
		}

			// Check if TypoScript is found for full path (controller and action)
		if (!$this->isTypoScriptFoundForCurrentRequest()) {
				// Remove the action, and check if TypoScript is found for the controller
			$this->typoScriptPath = substr($this->typoScriptPath, 0, strrpos($this->typoScriptPath, '/'));
			if (!$this->isTypoScriptFoundForCurrentRequest()) {
					// Fall back to default path
				$this->typoScriptPath = 'Application';
			}
		}
	}

}
?>