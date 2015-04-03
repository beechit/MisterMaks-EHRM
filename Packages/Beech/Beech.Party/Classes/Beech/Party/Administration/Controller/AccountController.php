<?php
namespace Beech\Party\Administration\Controller;

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
use TYPO3\Flow\Security\Account as Account;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class AccountController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 * @Flow\Inject
	 */
	protected $accountFactory;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 * @Flow\Inject
	 */
	protected $policyService;

	/**
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 * @Flow\Inject
	 */
	protected $hashService;

	/**
	 * Shows a list of accounts
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('accounts', $this->accountRepository->findAll());
	}

	/**
	 * Shows a single account object
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to show
	 * @return void
	 */
	public function showAction(Account $account) {
		$this->view->assign('account', $account);
	}

	/**
	 * Shows a form for creating a new account object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function newAction(\Beech\Party\Domain\Model\Person $person = NULL) {
		$this->view->assign('person', $person);
		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('roles', $this->policyService->getRoles());
	}

	/**
	 * Adds the given new account object to the account repository
	 *
	 * @param string $accountIdentifier
	 * @param string $password
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @param array $roles
	 * @return void
	 */
	public function createAction($accountIdentifier, $password, $person, array $roles) {
		$authenticationProviderName = 'DefaultProvider';

		$account = $this->accountFactory->createAccountWithPassword($accountIdentifier, $password, $roles, $authenticationProviderName);
		$account->setParty($person);
		$this->accountRepository->add($account);

		$this->addFlashMessage($this->translator->translateById('Created', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));

		$this->redirect('edit', NULL, NULL, array('account' => $account));
	}

	/**
	 * Shows a form for editing an existing account object
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to edit

	 * @return void
	 */
	public function editAction(Account $account) {
		$this->view->assign('roles', $this->policyService->getRoles());
		$this->view->assign('account', $account);
	}

	/**
	 * Updates the given account object
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to update
	 * @param string $password
	 * @return void
	 */
	public function updateAction(Account $account, $password) {
		if (!empty($password)) {
			$account->setCredentialsSource($this->hashService->hashPassword($password, 'default'));
		}
		$this->accountRepository->update($account);
		$this->addFlashMessage($this->translator->translateById('Updated', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->emberRedirect('#/administration/userManagement');
	}

	/**
	 * Removes the given account object from the account repository
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to delete
	 * @return void
	 */
	public function deleteAction(Account $account) {
		$this->accountRepository->remove($account);
		$this->persistenceManager->persistAll();
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->emberRedirect('#/administration/userManagement');
	}

	/**
	 * Redirect to list action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('list');
	}

}

?>