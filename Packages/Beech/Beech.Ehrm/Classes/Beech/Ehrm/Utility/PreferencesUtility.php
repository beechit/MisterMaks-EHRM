<?php
namespace Beech\Ehrm\Utility;

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
 * @Flow\Scope("singleton")
 */
class PreferencesUtility {

	const CATEGORY_APPLICATION = 'application';

	const CATEGORY_USER = 'user';

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationPreferencesRepository
	 * @Flow\Inject
	 */
	protected $applicationPreferencesRepository;

	/**
	 * @var \Beech\Ehrm\Domain\Model\ApplicationPreferences
	 */
	protected $applicationPreferences;

	/**
	 * @var \Beech\Ehrm\Domain\Model\PersonPreferences
	 */
	protected $userPreferences;

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setApplicationPreference($key, $value) {
		$preferences = $this->getApplicationPreferences();
		$preferences->set($key, $value);
		$this->applicationPreferencesRepository->update($preferences);
	}

	/**
	 * @param string $key
	 * @param boolean $userSettingsHavePreference
	 * @return mixed
	 */
	public function getApplicationPreference($key, $userSettingsHavePreference = TRUE) {
		if ($userSettingsHavePreference && $this->securityContext->isInitialized()) {
			// User preference will override the application settings
			$userPreferences = $this->getUserPreference($key);
			if ($userPreferences !== NULL) {
				return $userPreferences;
			}
		}
		return $this->getApplicationPreferences()->get($key);
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\ApplicationPreferences
	 */
	public function getApplicationPreferences() {
		if (!isset($this->applicationPreferences)) {
			$this->applicationPreferences = $this->applicationPreferencesRepository->getPreferences();
		}
		return $this->applicationPreferences;
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\PersonPreferences
	 */
	public function getUserPreferenceDocument() {

		if (!isset($this->userPreferences)) {

			if ($this->getCurrentUser() === NULL) {
				$this->userPreferences = new \Beech\Ehrm\Domain\Model\PersonPreferences();
			} else {
				$this->userPreferences = $this->securityContext->getAccount()->getParty()->getPreferences();
			}
		}

		return $this->userPreferences;
	}

	/**
	 * Get a preference for the currently logged in user
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getUserPreference($key) {
		return $this->getUserPreferenceDocument()->get($key);
	}

	/**
	 * Get current logged in user
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getCurrentUser() {

		$currentUser = NULL;

		if ($this->securityContext
			&& $this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
			&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person
		) {
			$currentUser = $this->securityContext->getAccount()->getParty();
		}

		return $currentUser;
	}
}

?>