<?php
namespace Beech\Ehrm\View;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 19:46
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class TypoScriptView extends \TYPO3\TypoScript\View\TypoScriptView {

	/**
	 * @var string
	 */
	protected $typoScriptPathPattern = 'resource://Beech.Ehrm/Private/TypoScripts/';

	/**
	 * @var \TYPO3\Fluid\View\TemplateView
	 * @Flow\Inject
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

		if ($this->controllerContext->getRequest()->hasArgument('@typoScriptPath')) {
			$this->typoScriptPath .= '/' . $this->controllerContext->getRequest()->getArgument('@typoScriptPath');
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

	/**
	 * Determine whether we are able to find TypoScript at the requested position
	 *
	 * @return boolean TRUE if TypoScript exists at $this->typoScriptPath; FALSE otherwise
	 */
	protected function isTypoScriptFoundForCurrentRequest() {
		$typoScriptRuntime = new \TYPO3\TypoScript\Core\Runtime($this->parsedTypoScript, $this->controllerContext);
		return $typoScriptRuntime->canRender($this->typoScriptPath);
	}


}
?>