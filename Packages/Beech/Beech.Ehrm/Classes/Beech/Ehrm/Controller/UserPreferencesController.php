<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-09-12 01:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Setup controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class UserPreferencesController extends AbstractController {

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Ehrm\Log\ApplicationLoggerInterface
	 * @Flow\Inject
	 */
	protected $applicationLogger;

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
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('locale', $this->preferenceUtility->getUserPreference('locale'));
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages());
	}

	/**
	 * Update action
	 *
	 * @param string $locale
	 * @return void
	 */
	public function updateAction($locale = 'en_EN') {
		$this->preferenceUtility->setUserPreference('locale', $locale);
		$this->redirect('index');
	}

}

?>