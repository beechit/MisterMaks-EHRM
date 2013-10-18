<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Main application controller for Mister Maks
 *
 * @Flow\Scope("singleton")
 */
class ApplicationController extends AbstractController {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW\Inject
	 */
	protected $authenticationManager;

	/**
	 * @return void
	 */
	public function indexAction() {

			// trigger setup wizard
		if(!$this->preferencesUtility->getApplicationPreference('setupWizardComplete')) {
			// Commented until setup wizard is be fixed
			//$this->view->assign('emberRedirect', '/settings/setupwizard');
		}
	}

	/**
	 * Try to startup the websocket server when this isn't running
	 *
	 * @return void
	 */
	public function pingWebSocketServerAction() {

		\Beech\Socket\Service\SendCommands::startServer();

		throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
	}

}

?>