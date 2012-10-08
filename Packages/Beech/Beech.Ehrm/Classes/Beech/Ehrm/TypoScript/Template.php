<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-09-12 08:37
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * MisterMaks TemplateImplementation for TypoScript
 *
 * @Flow\Scope("prototype")
 */
class Template extends \TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation {

	/**
	 * @return mixed
	 */
	public function evaluate() {
		$fluidTemplate = new \TYPO3\Fluid\View\StandaloneView($this->tsRuntime->getControllerContext()->getRequest());

		$templatePath = $this->tsValue('templatePath');
		if ($templatePath === NULL) {
			$templatePath = 'resource://' . $this->tsRuntime->getControllerContext()->getRequest()->getControllerPackageKey() .
				'/Private/Templates/' . $this->tsRuntime->getControllerContext()->getRequest()->getControllerName() .
				'/' . ucfirst($this->tsRuntime->getControllerContext()->getRequest()->getControllerActionName()) .
				'.' . $this->tsRuntime->getControllerContext()->getRequest()->getFormat();
		}

		$fluidTemplate->setTemplatePathAndFilename($templatePath);

		$partialRootPath = $this->tsValue('partialRootPath');
		if ($partialRootPath !== NULL) {
			$fluidTemplate->setPartialRootPath($partialRootPath);
		}

		$layoutRootPath = $this->tsValue('layoutRootPath');
		if ($layoutRootPath !== NULL) {
			$fluidTemplate->setLayoutRootPath($layoutRootPath);
		}

			// Assign all variables already assigned to the view in the action
		$fluidTemplate->assignMultiple($this->tsRuntime->getCurrentContext());

		foreach ($this->variables as $key => $value) {
			$evaluatedValue = $this->tsRuntime->evaluateProcessor($key, $this, $value);
			$fluidTemplate->assign($key, $evaluatedValue);
		}

		$fluidTemplate->assign('fluidTemplateTsObject', $this);

		$sectionName = $this->tsValue('sectionName');

		if ($sectionName !== NULL) {
			return $fluidTemplate->renderSection($sectionName);
		} else {
			return $fluidTemplate->render();
		}
	}
}