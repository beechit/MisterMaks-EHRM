<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
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
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 * @FLOW3\Inject
	 */
	protected $accountRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @FLOW3\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\FLOW3\Security\AccountFactory
	 * @FLOW3\Inject
	 */
	protected $accountFactory;

	/**
	 * Create a user
	 *
	 * @param string $username Username
	 * @param string $password Password
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $roles Comma separated list of roles
	 * @return void
	 */
	public function createCommand($username, $password, $firstName, $lastName, $roles) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if ($account instanceof \TYPO3\FLOW3\Security\Account) {
			$this->outputLine('User "%s" already exists.', array($username));
			return;
		}

		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName(NULL, $firstName, NULL, $lastName));
		$this->personRepository->add($person);

		$account = $this->accountFactory->createAccountWithPassword($username, $password, explode(',', $roles), 'DefaultProvider');
		$account->setParty($person);
		$this->accountRepository->add($account);
		$this->outputLine('Created account "%s".', array($username));
	}

	/**
	 * Set a setting for a user
	 *
	 * Because default value is set to NULL its very important to use syntax
	 * --value NL_nl cause otherwise it will not work
	 *
	 * Example:
	 *
	 * CORRECT
	 * ./flow3 user:setting admin locale --value NL_nl
	 *
	 * NOT CORRECT
	 * ./flow3 user:setting admin locale NL_nl
	 *
	 * @param string $username
	 * @param string $setting
	 * @param string $value
	 * @return void
	 */
	public function settingCommand($username, $setting, $value = NULL) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if (!$account instanceof \TYPO3\FLOW3\Security\Account) {
			$this->outputLine('Account "%s" not found', array($username));
		}

		if (!$account->getParty() instanceof \Beech\Party\Domain\Model\Person) {
			$this->outputLine('Account "%s" does not have a valid Person object ', array($username));
		}

		if ($value === NULL) {
			$this->outputLine('Setting "%s" for "%s" contains value "%s"', array(
				$setting,
				$account->getParty()->getName()->getFullName(),
				$account->getParty()->getPreferences()->get($setting)
			));
		} else {
			$account->getParty()->getPreferences()->set($setting, $value);
			$this->accountRepository->update($account);

			$this->outputLine('Setting "%s" for "%s" set to "%s"', array(
				$setting,
				$account->getParty()->getName()->getFullName(),
				$value
			));
		}

		$this->personRepository->update($account->getParty());
	}

}

?>