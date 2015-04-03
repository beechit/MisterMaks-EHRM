<?php
namespace Beech\CLA\ViewHelpers;
/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
			if (isset($formValues['article-section-'. $articleId .'-isSelected'])
				&& $formValues['article-section-'. $articleId .'-isSelected'] === 'TRUE'
				|| !isset($formValues['article-section-'. $articleId .'-isSelected'])) {
				$view->assign('articleIndex' ,++self::$articleIndex);
				return $view->render();
			}
		}
		return '';
	}

}