<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 05-06-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * setup command controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class UserCommandController extends \TYPO3\FLOW3\Cli\CommandController {

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\Party\Domain\Repository\PartyRepository
	 */
	protected $partyRepository;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 *
	 * @param string $username Username
	 * @param string $password Password
	 * @param string $roles Comma separated list of roles
	 * @return void
	 */
	public function createCommand($username, $password, $roles) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if ($account instanceof \TYPO3\FLOW3\Security\Account) {
			$this->outputLine('User "%s" already exists.', array($username));
			return;
		}

		$account = $this->accountFactory->createAccountWithPassword($username, $password, explode(',', $roles), 'DefaultProvider');
		$this->accountRepository->add($account);
		$this->outputLine('Created account "%s".', array($username));
	}

}

?>