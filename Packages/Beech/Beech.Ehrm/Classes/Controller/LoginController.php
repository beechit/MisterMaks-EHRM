<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 03-05-12 22:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Login controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class LoginController extends AbstractController {

	/**
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW3\Inject
	 */
	protected $authenticationManager;

	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 * @FLOW3\Inject
	 */
	protected $accountRepository;

	/**
	 * @param string $username
	 * @return void
	 */
	public function loginAction($username = NULL) {
		$this->view->assign('username', $username);
	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * On successful authentication redirects to the status view, otherwise returns
	 * to the login screen.
	 *
	 * @return void
	 */
	public function authenticateAction() {
		$authenticated = FALSE;
		try {
			$this->authenticationManager->authenticate();
			$authenticated = TRUE;
		} catch (\Exception $exception) {
			$message = $exception->getMessage();
		}

		if ($authenticated) {
			$this->redirect('status');
		} else {
			$this->addFlashMessage($message);
			$this->redirect('login');
		}
	}

	/**
	 * Logs out a - possibly - currently logged in account.
	 *
	 * @return void
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();
		$this->addFlashMessage('Successfully logged out.');
		$this->redirect('login');
	}

	/**
	 * @return void
	 */
	public function statusAction() {
		$this->view->assign('activeTokens', $this->securityContext->getAuthenticationTokens());
	}

}

?>