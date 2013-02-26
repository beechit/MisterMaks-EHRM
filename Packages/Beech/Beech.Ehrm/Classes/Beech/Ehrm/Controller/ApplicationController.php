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
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW\Inject
	 */
	protected $authenticationManager;

	/**
	 * @return void
	 */
	public function indexAction() {
		if ($this->authenticationManager->isAuthenticated() === FALSE) {
			$this->redirect('intro');
		}
	}

	/**
	 * @return void
	 */
	public function introAction() {
		if ($this->authenticationManager->isAuthenticated() === TRUE) {
			$this->redirect('index');
		}
	}

}

?>