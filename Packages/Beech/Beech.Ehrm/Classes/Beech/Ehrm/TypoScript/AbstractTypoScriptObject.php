<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-09-12 11:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * This is the base TypoScriptObject to use in Mister Maks. Every evaluate() method should
 * call $this->initializeView() first to use the automatic path resolving.
 *
 * @Flow\Scope("prototype")
 */
abstract class AbstractTypoScriptObject extends \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject {

	/**
	 * @var \TYPO3\Fluid\View\StandaloneView
	 */
	protected $view;

	/**
	 * @return void
	 */
	protected function initializeView() {
		$this->view = new \TYPO3\Fluid\View\StandaloneView();
		$this->view->setControllerContext($this->tsRuntime->getControllerContext());
		$templatePath = $this->tsValue('templatePath');
		if ($templatePath === NULL) {
			$templatePath = 'resource://Beech.Ehrm/Private/Templates/TypoScriptObjects/' . substr(get_class($this), strrpos(get_class($this), '\\') + 1) . '.' . $this->tsRuntime->getControllerContext()->getRequest()->getFormat();
		}
		$this->view->assign('fluidTemplateTsObject', $this);

		$this->view->setTemplatePathAndFilename($templatePath);
	}

}

?>