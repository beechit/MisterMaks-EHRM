<?php
namespace Beech\CLA\ViewHelpers;
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-06-2013 14:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class RenderContractArticleTextViewHelper
 *
 * @package Beech\CLA\ViewHelpers
 */
class RenderContractArticleTextViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {


	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('element', 'Beech\CLA\FormElements\ContractArticleFormElement', 'ContractArticle form element');
	}

	/**
	 * @param \TYPO3\Form\Core\Model\Renderable\RootRenderableInterface $renderable
	 * @return string the rendered articleText
	 */
	public function render() {
		$fluidFormRenderer = $this->viewHelperVariableContainer->getView();
		$formRuntime = $fluidFormRenderer->getFormRuntime();
		$formState = $formRuntime->getFormState();

		if($this->hasArgument('element')) {
			return $this->arguments['element']->generateArticleText($formState);
		} else {
			return '';
		}
	}

}