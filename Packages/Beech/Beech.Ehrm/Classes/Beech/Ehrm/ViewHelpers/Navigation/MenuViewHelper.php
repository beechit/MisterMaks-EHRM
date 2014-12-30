<?php
namespace Beech\Ehrm\ViewHelpers\Navigation;

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
 *
 * @Flow\Scope("prototype")
 */
class MenuViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

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
	 * Render the menu
	 *
	 * @param array $items
	 * @param string $class
	 * @return string
	 */
	public function render(array $items, $class = 'nav') {
		$view = $this->getView();

		$view->assignMultiple(array(
			'items' => $items,
			'settings' => $this->settings,
			'class' => $class,
		));

		return $view->render();
	}

}
?>