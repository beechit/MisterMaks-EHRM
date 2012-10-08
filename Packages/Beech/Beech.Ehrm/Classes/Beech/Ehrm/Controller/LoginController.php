<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Login controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class LoginController extends \TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController {

	/**
	 * @param string $username
	 * @return void
	 */
	public function loginAction($username = NULL) {
		$this->view->assign('username', $username);
	}

	/**
	 * @param \TYPO3\Flow\Mvc\ActionRequest $originalRequest
	 * @return void
	 */
	public function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {
		$this->redirect('index', 'Standard', 'Beech.Ehrm');
	}

	/**
	 * Logs out a - possibly - currently logged in account.
	 *
	 * @return void
	 */
	public function logoutAction() {
		parent::logoutAction();
		$this->addFlashMessage('Successfully logged out.');
		$this->redirect('login');
	}

}

?>