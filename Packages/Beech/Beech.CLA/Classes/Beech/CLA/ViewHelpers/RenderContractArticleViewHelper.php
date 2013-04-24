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
class RenderContractArticleViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	protected static $articleIndex = 0;
	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

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
	 * Get a StandaloneView used for rendering the component
	 *
	 * @return \TYPO3\Fluid\View\StandaloneView
	 */
	protected function getView() {
		$view = new \TYPO3\Fluid\View\StandaloneView($this->controllerContext->getRequest());
		$view->setFormat('html');
		if (is_file($this->settings['viewHelpers']['templates'][get_class($this)])) {
			$view->setPartialRootPath($this->settings['viewHelpers']['partialRootPath']);
			$view->setTemplatePathAndFilename($this->settings['viewHelpers']['templates'][get_class($this)]);
		}
		return $view;
	}

	/**
	 * @param \TYPO3\Form\Core\Model\Renderable\RootRenderableInterface $renderable
	 * @return string the rendered articleText
	 */
	public function render() {

		$fluidFormRenderer = $this->viewHelperVariableContainer->getView();
		$formRuntime = $fluidFormRenderer->getFormRuntime();
		$formState = $formRuntime->getFormState();
		$view = $this->getView();
		if($this->hasArgument('element')) {
			$formValues = $formState->getFormValues();
			$articleId = $this->arguments['element']->getContractArticle()->getArticleId();
			$view->assign('settings' ,$this->settings);
			$view->assign('element' ,$this->arguments['element']);
			$view->assign('articleText', $this->arguments['element']->generateArticleText($formState));
			if ($formValues['article-section-'. $articleId .'-isSelected'] === 'TRUE') {
				$view->assign('articleIndex' ,++self::$articleIndex);
				return $view->render();
			}
		}
		return '';
	}

}