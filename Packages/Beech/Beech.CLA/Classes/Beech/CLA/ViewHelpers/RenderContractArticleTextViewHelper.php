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