<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
abstract class AbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @FLOW3\Inject
	 */
	protected $settingsHelper;

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'Beech\Ehrm\View\TemplateView';

	/**
	 * @return \TYPO3\FLOW3\Mvc\View\ViewInterface
	 */
	public function resolveView() {
		$view = parent::resolveView();

		$renderingContext = new \TYPO3\Fluid\Core\Rendering\RenderingContext();
		$renderingContext->setControllerContext($this->controllerContext);

		$view->setRenderingContext($renderingContext);
		return $view;
	}

	/**
	 * @param \TYPO3\FLOW3\Mvc\View\ViewInterface $view
	 * @return void
	 */
	public function initializeView(\TYPO3\FLOW3\Mvc\View\ViewInterface $view) {
		if ($this->request->getFormat() === 'html') {
			$view->assign('mainMenuItems', $this->settingsHelper->getMenuItems('main'));
			$view->assign('actionMenuItems', $this->settingsHelper->getMenuItems('action'));
			$view->assign('accountMenuItems', $this->settingsHelper->getMenuItems('account'));
			$view->assign('globalConfiguration', json_encode(array(
				'restNotificationUri' => $this->uriBuilder->reset()->setFormat('json')->uriFor('list', array(), 'Rest\Notification', 'Beech.Party')
			)));
		}
	}

}

?>