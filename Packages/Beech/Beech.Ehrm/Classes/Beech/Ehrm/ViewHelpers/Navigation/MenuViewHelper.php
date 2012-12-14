<?php
namespace Beech\Ehrm\ViewHelpers\Navigation;


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
	 * @param array $classNames
	 * @return string
	 */
	public function render(array $items, array $classNames = array('nav')) {
		$view = $this->getView();

		$view->assignMultiple(array(
			'items' => $items,
			'settings' => $this->settings,
			'classNames' => implode(' ', $classNames),
		));

		return $view->render();

	}

}
?>