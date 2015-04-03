<?php
namespace Beech\Ehrm\Command;

/*                                                                        *
* This script belongs to beechit/mrmaks.		                  *
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
 * setup command controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class UserCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 * @Flow\Inject
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
		if ($account instanceof \TYPO3\Flow\Security\Account) {
			$this->outputLine('User "%s" already exists.', array($username));
			return;
		}

		$person = new \Beech\Party\Domain\Model\Person();

		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName));
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
	 * ./flow3 user:setting admin locale --value nl_NL
	 *
	 * NOT CORRECT
	 * ./flow3 user:setting admin locale nl_NL
	 *
	 * @param string $username
	 * @param string $setting
	 * @param string $value
	 * @return void
	 */
	public function settingCommand($username, $setting, $value = NULL) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if (!$account instanceof \TYPO3\Flow\Security\Account) {
			$this->outputLine('Account "%s" not found', array($username));
		}

		/** @var \Beech\Party\Domain\Model\Person $person */
		$person = $account->getParty();

		if (!$person instanceof \Beech\Party\Domain\Model\Person) {
			$this->outputLine('Account "%s" does not have a valid Person object ', array($username));
		}

		if ($value === NULL) {
			$this->outputLine('Setting "%s" for "%s" contains value "%s"', array(
				$setting,
				$person->getName()->getFullName(),
				$person->getPreferences()->get($setting)
			));
		} else {
			$person->getPreferences()->set($setting, $value);
			$this->personRepository->update($person);

			$this->outputLine('Setting "%s" for "%s" set to "%s"', array(
				$setting,
				$person->getName()->getFullName(),
				$value
			));
		}
	}

}

?>