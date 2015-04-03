<?php
namespace Beech\Ehrm\Controller;

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
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @Flow\Inject
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionInterface
	 */
	protected $session;

	/**
	 * @param string $username
	 * @return void
	 */
	public function loginAction($username = NULL) {
		if ($this->authenticationManager->isAuthenticated()) {
			$this->redirect('index', 'Application', 'Beech.Ehrm');
		}
		$this->view->assign('applicationConfigured', $this->preferencesUtility->getApplicationPreference('company') !== NULL);
		$this->view->assign('username', $username);
	}

	/**
	 * @param \TYPO3\Flow\Mvc\ActionRequest $originalRequest
	 * @return void
	 */
	public function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {
		$this->session->putData('accountIdentifier', $this->securityContext->getAccount()->getAccountIdentifier());
		$this->session->putData('partyId', $this->securityContext->getAccount()->getParty()->getId());
		if ($this->request->hasArgument('modal')) {
			return 'ok';
		} elseif ($originalRequest !== NULL) {
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
		$this->redirect('index', 'Application');
	}

}

?>