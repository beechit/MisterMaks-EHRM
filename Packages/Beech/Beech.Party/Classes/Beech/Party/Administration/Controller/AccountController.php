<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new account object to the account repository
	 *
	 * @param string $accountIdentifier
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $roles
	 * @return void
	 */
	public function createAction($accountIdentifier, $password, $firstName, $lastName, $roles) {
		$person = new \Beech\Party\Domain\Model\Person();
		$person->addPersonName(new \TYPO3\Party\Domain\Model\PersonName('', $firstName, NULL, $lastName));
		$this->personRepository->add($person);

		$authenticationProviderName = 'DefaultProvider';

		$account = $this->accountFactory->createAccountWithPassword($accountIdentifier, $password, array($roles), $authenticationProviderName);
		$account->setParty($person);
		$this->accountRepository->add($account);
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing account object
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to edit
	 * @return void
	 */
	public function editAction(Account $account) {
		$this->view->assign('account', $account);
	}

	/**
	 * Updates the given account object
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to update
	 * @return void
	 */
	public function updateAction(Account $account) {
		$this->accountRepository->update($account);
		$this->addFlashMessage('Updated the account.');
		$this->redirect('list');
	}

	/**
	 * Removes the given account object from the account repository
	 *
	 * @param \TYPO3\Flow\Security\Account $account The account to delete
	 * @return void
	 */
	public function deleteAction(Account $account) {
		$this->accountRepository->remove($account);
		$this->addFlashMessage('Deleted a account.');
		$this->redirect('list');
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