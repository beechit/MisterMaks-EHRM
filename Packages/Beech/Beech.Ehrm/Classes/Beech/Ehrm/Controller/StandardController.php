<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends AbstractController {

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW\Inject
	 */
	protected $authenticationManager;

	/**
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();
		if ($this->authenticationManager->isAuthenticated() === FALSE) {
			$this->redirect('login', 'Login');
		}
	}

	/**
	 *
	 */
	public function indexAction() {
	}

}

?>