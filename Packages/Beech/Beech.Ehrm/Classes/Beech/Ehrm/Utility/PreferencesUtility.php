<?php
namespace Beech\Ehrm\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-12-12 09:17
 * All code (c) Beech Applications B.V. all rights reserved
 */

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

			if (!$this->securityContext->isInitialized()
				|| !$this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
				|| !$this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person
			) {
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
}

?>