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
class PreferenceUtility {

	const CATEGORY_APPLICATION = 'application';
	const CATEGORY_USER = 'user';

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\PreferenceRepository
	 * @Flow\Inject
	 */
	protected $preferenceRepository;

	/**
	 * @var \Beech\Ehrm\Domain\Model\Preference
	 */
	protected $applicationPreference;

	/**
	 * @var \Beech\Ehrm\Domain\Model\Preference
	 */
	protected $userPreference;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setApplicationPreference($key, $value) {
		$document = $this->getApplicationPreferenceDocument();
		$document->set($key, $value);
		$this->preferenceRepository->update($document);
	}

	/**
	 * @param string $key
	 * @param boolean $userCategoryHasPreference
	 * @return mixed
	 */
	public function getApplicationPreference($key, $userCategoryHasPreference = TRUE) {
		if ($userCategoryHasPreference && $this->securityContext->isInitialized()) {
				// User preference will override the application setting
			$userPreference = $this->getUserPreference($key);
			if ($userPreference !== NULL) {
				return $userPreference;
			}
		}
		return $this->getApplicationPreferenceDocument()->get($key);
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Preference
	 */
	public function getApplicationPreferenceDocument() {
		if (!isset($this->applicationPreference)) {
			$this->applicationPreference = $this->getPreferenceDocument(self::CATEGORY_APPLICATION);
		}

		return $this->applicationPreference;
	}

	/**
	 * @throws \Beech\Ehrm\Exception\NoActiveSessionException
	 * @return \Beech\Ehrm\Domain\Model\Preference
	 */
	public function getUserPreferenceDocument() {
		if (!$this->securityContext->isInitialized()
				|| !$this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
				|| !$this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
			throw new \Beech\Ehrm\Exception\NoActiveSessionException('No active session');
		}

		if (!isset($this->userPreference)) {
			$this->userPreference = $this->getPreferenceDocument(self::CATEGORY_USER, $this->securityContext->getAccount()->getParty());
		}

		return $this->userPreference;
	}

	/**
	 * Set a preference for the currently logged in user
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function setUserPreference($key, $value) {
		$document = $this->getUserPreferenceDocument();
		$document->set($key, $value);
		$this->preferenceRepository->update($document);
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
	 * @param object $model
	 * @param string $category
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setModelPreference($model, $category, $key, $value) {
		$document = $this->getPreferenceDocument($category, $model);
		$document->set($key, $value);
		$this->preferenceRepository->update($document);
	}

	/**
	 * @param object $model
	 * @param string $category
	 * @param string $key
	 * @return mixed
	 */
	public function getModelPreference($model, $category, $key) {
		return $this->getPreferenceDocument($category, $model)->get($key);
	}

	/**
	 * Fetches a preference document based on a category and a possible model.
	 * It will use an existing document if it can find one, otherwise a new one
	 * is created.
	 *
	 * @param string $category
	 * @param mixed $model
	 * @return \Beech\Ehrm\Domain\Model\Preference
	 */
	protected function getPreferenceDocument($category, $model = NULL) {
		if ($model !== NULL) {
			$result = $this->preferenceRepository->findByModelAndCategory($model, $category);
			if (count($result) > 0) {
				return $result[0];
			}

			if (is_object($model) && method_exists($model, 'getId')) {
				$modelIdentifier = $model->getId();
			} else {
				$modelIdentifier = $this->persistenceManager->getIdentifierByObject($model);
			}

			$preferenceDocument = new \Beech\Ehrm\Domain\Model\Preference($category);
			$preferenceDocument->setIdentifier($modelIdentifier);
			$this->preferenceRepository->add($preferenceDocument);

			return $preferenceDocument;
		}

		$result = $this->preferenceRepository->findByCategory($category);
		if (count($result) > 0) {
			return $result[0];
		}

		$preferenceDocument = new \Beech\Ehrm\Domain\Model\Preference($category);
		$this->preferenceRepository->add($preferenceDocument);
		return $preferenceDocument;
	}

}

?>