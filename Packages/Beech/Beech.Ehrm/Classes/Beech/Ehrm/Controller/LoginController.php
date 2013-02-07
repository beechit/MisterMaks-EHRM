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
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW\Inject
	 */
	protected $authenticationManager;

	/**
	 * @param string $username
	 * @return void
	 */
	public function loginAction($username = NULL) {
		if ($this->authenticationManager->isAuthenticated()) {
			$this->redirect('index', 'Application', 'Beech.Ehrm');
		}
		$this->view->assign('applicationConfigured', $this->preferenceUtility->getApplicationPreference('company') !== NULL);
		$this->view->assign('username', $username);
	}

	/**
	 * @param \TYPO3\Flow\Mvc\ActionRequest $originalRequest
	 * @return void
	 */
	public function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {
		if ($originalRequest !== NULL) {
			$this->redirectToRequest($originalRequest);
		}
		$this->redirect('index', 'Application', 'Beech.Ehrm');
	}

	/**
	 * Is called if authentication failed.
	 *
	 * Override default method.
	 *
	 * @param \TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception The exception thrown when the authentication process fails
	 * @return void
	 */
	protected function onAuthenticationFailure(\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception = NULL) {}

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors. Override this to customize
	 * the flash message in your action controller.
	 *
	 * This method is used to overwrite default error messages template TYPO3.Flow package
	 *
	 * @return \TYPO3\Flow\Error\Error The flash message
	 */
	protected function getErrorFlashMessage() {
		return new \TYPO3\Flow\Error\Message(
			$this->translator->translateById('message.authentication.failed.wrongCredentials', array(), NULL, NULL, 'Main', 'Beech.Ehrm'),
			NULL,
			array(),
			$this->translator->translateById('message.authentication.failed', array(), NULL, NULL, 'Main', 'Beech.Ehrm')
		);
	}

	/**
	 * Logs out a - possibly - currently logged in account.
	 *
	 * @return void
	 */
	public function logoutAction() {
		parent::logoutAction();
		$this->addFlashMessage('Successfully logged out.');
		$this->redirect('intro', 'Application');
	}

}

?>